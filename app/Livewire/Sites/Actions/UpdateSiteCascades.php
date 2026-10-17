<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Contracts\SiteInterface;
use App\Models\Site\SiteRoute;
use Toaster;

class UpdateSiteCascades extends Component
{
    public object $site;
  
    
    public function mount( Site $site)
    {
         $this->authorize('update', $site);
        $this->site = $site;

       
    }

    /**
     * Unhooks $site_code from this site in site_routes - the reverse of
     * ShowSearchedSites::addSiteToCascades(), on every route at once.
     *
     * Every row whose drawn path runs through this site -> $site_code goes:
     * the hops naming $site_code as the child, and the hops further down
     * that only reached the hub through that link. A site below keeps
     * whatever other routes it has.
     *
     * Like the add, this edits the sheet's rows, so the next import of the
     * xlsx brings the link back unless the planners remove it there too.
     */
    #[On('removeCascade')]
    public function deleteCascade(string $site_code)
    {
        $this->authorize('update', $this->site);

        $nodal = strtoupper(trim($this->site->site_code));
        $child = strtoupper(trim($site_code));
        $link = "$nodal>$child";

        // the table lists indirect cascades too; only a direct link can be cut here
        if (! SiteRoute::where('parent_code', $nodal)->where('child_code', $child)->exists()) {
            Toaster::error("$child is not cascaded directly on $nodal")->duration(7000);
            return;
        }

        // LIKE narrows it down; the exact check stops 0904CA>1 matching 0904CA>12
        $ids = SiteRoute::where('path', 'like', "%$link%")
            ->get(['id', 'path'])
            ->filter(function ($hop) use ($link) {
                $path = ">{$hop->path}>";

                return str_contains($path, ">$link>");
            })
            ->pluck('id');

        SiteRoute::whereIn('id', $ids)->delete();

        Toaster::success("$child has been removed from $nodal cascades")->duration(7000);
        return redirect()->route('site.cascades.update', ['site' => $this->site->site_code]);
    }
    
    public function render()
    {
        return view('livewire.sites.actions.update-site-cascades');
    }
}
