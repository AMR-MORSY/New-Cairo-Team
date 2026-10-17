<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Models\Site\SiteRoute;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;

class ShowSearchedSites extends Component
{

    public $props;

    public $sites;

    public  $previousUrl;

    public function mount($target,$search,$updateSite=null)
    {
         $this->previousUrl = url()->previous();

        $this->sites = Site::where('site_code', 'like', "%$search%")->orWhere('site_name', 'like', "%$search%")->get();

        $this->props = [
            'title' => "Sites",
            'data' => $this->sites,
            'target' => $target,
            'updateSite'=>$updateSite

        ];



    }

    /**
     * Hangs $site_code under $update_site_code (the nodal) in site_routes.
     *
     * The new hop extends the nodal's drawn routes to its hub: one row for
     * every route with no red site anywhere on it, the nodal included. If
     * every route has a red site, nothing is added.
     *
     * The rows are written under the sheet's own source, so the next import of
     * that sheet replaces them unless the planners draw the link in the xlsx.
     */
    #[On('addSiteToCascades')]
    public function addSiteToCascades($site_code, $update_site_code)
    {
        $child = strtoupper(trim((string) $site_code));
        $nodal = strtoupper(trim((string) $update_site_code));

        if ($nodal === '') {
            Toaster::error('No site selected to add cascades to');
            return;
        }

        if ($child === $nodal) {
            Toaster::error('Can not cascade on itself');
            return;
        }

        if (SiteRoute::where('parent_code', $nodal)->where('child_code', $child)->exists()) {
            Toaster::error("$child is already cascaded on $nodal")->duration(7000);
            return;
        }

        /*
         * Every drawn route to the nodal: the prefix of each path, cut at the
         * nodal. Not deduplicated by chain - the sheet can draw the same chain
         * twice in different colours (7448CA via 3758CA is yellow on one row,
         * red on another), and each drawing is judged on its own fills.
         */
        $routes = [];

        foreach (SiteRoute::where('path', 'like', "%$nodal%")->orderBy('sheet_row')->get() as $hop) {
            $chain = explode('>', $hop->path);
            $at = array_search($nodal, $chain, true);

            if ($at === false) {
                continue;
            }

            $routes[] = [
                'hop' => $hop,
                'chain' => array_slice($chain, 0, $at + 1),
                'fills' => array_slice(explode(',', (string) $hop->path_fills), 0, $at + 1),
                'names' => array_slice($hop->path_names ?? [], 0, $at + 1),
            ];
        }

        if (! $routes) {
            Toaster::error("$nodal has no route to a hub in the routing sheet")->duration(7000);
            return;
        }

        // one per chain: the same chain drawn clean twice is still one route
        $clean = [];

        foreach ($routes as $r) {
            if (! in_array('red', $r['fills'], true)) {
                $clean[implode('>', $r['chain'])] ??= $r;
            }
        }

        if (! $clean) {
            Toaster::error("Every route from $nodal to its hub passes a red site")->duration(7000);
            return;
        }

        // the child already sits upstream on a route: hanging it there would loop
        $clean = array_filter($clean, fn ($r) => ! in_array($child, $r['chain'], true));

        if (! $clean) {
            Toaster::error("$child is on the route from $nodal to its hub")->duration(7000);
            return;
        }

        $childName = Site::where('site_code', $child)->value('site_name');

        DB::transaction(function () use ($clean, $nodal, $child, $childName) {
            foreach ($clean as $route) {
                $source = $route['hop']->source;

                SiteRoute::create([
                    'source' => $source,
                    'hub_code' => $route['chain'][0],
                    'hub_name' => $route['hop']->hub_name,
                    'parent_code' => $nodal,
                    'child_code' => $child,
                    'child_name' => $childName,
                    'level' => count($route['chain']),
                    // a row of its own at the bottom, so the Sheet grid does not merge it into a drawn row
                    'sheet_row' => (int) SiteRoute::where('source', $source)->max('sheet_row') + 1,
                    'path' => implode('>', [...$route['chain'], $child]),
                    'path_fills' => implode(',', [...$route['fills'], '']),
                    'path_names' => [...$route['names'], $childName],
                ]);
            }
        });

        $nodalName = Site::where('site_code', $nodal)->value('site_name');
        $routeCount = count($clean);

        Toaster::success("$childName has been added successfully to $nodalName cascades"
            . ($routeCount > 1 ? " on $routeCount routes" : ''))->duration(7000);
        return redirect()->route('site.cascades.update', ['site' => $nodal]);
    }

    public function goBack()
    {
        
        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.sites.actions.show-searched-sites');
    }
}
