<?php

namespace App\Livewire\Sites\Actions;

use App\Exports\SiteRoutesExport;
use App\Livewire\Tables\Site\NodalSitesTable;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use App\Services\Sites\SiteRouteMap;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

/**
 * The Routes tab of the CE VIP / TP / G.SQR pages: every site the page's
 * table lists, with its live routes from the planning sheet (shared parents
 * merged, sites joined by <>) and the batteries of every site on them.
 *
 * Red routes are left out, as everywhere else: a red cell is a site the
 * planners removed or rerouted.
 */
class SiteRoutesWithBatteries extends Component
{
    /** vip | TP | GSquare - the same keys as NodalSitesTable's target */
    public string $target = 'vip';

    public string $search = '';

    public function mount(string $target = 'vip'): void
    {
        $this->authorize('viewCESites', Site::class);
        $this->target = $target;
    }

    public function export()
    {
        $this->authorize('viewCESites', Site::class);

        return Excel::download(
            new SiteRoutesExport($this->exportRows()),
            "{$this->target}-sites-routes-" . now()->format('Y-m-d') . '.xlsx',
        );
    }

    /**
     * One entry per listed site:
     * code, name, category, tree (SiteRouteMap::routeTree), routes (as text),
     * and routeSites - every site on the routes in route order (hub first),
     * each with its name and batteries. A site with no live route lists itself.
     */
    public function cards(): array
    {
        $map = new SiteRouteMap();

        $sites = NodalSitesTable::sites($this->target, $map)
            ->when(trim($this->search) !== '', function ($query) {
                $term = '%' . trim($this->search) . '%';
                $query->where(fn ($q) => $q->where('site_code', 'like', $term)->orWhere('site_name', 'like', $term));
            })
            ->orderBy('site_code')
            ->get(['site_code', 'site_name', 'category']);

        $cards = [];
        $allCodes = [];

        foreach ($sites as $site) {
            $code = strtoupper($site->site_code);
            $routes = $map->liveRoutesTo($code);
            $tree = $map->treeFromRoutes($routes);
            $routeCodes = $tree ? $this->codesInOrder($tree) : [$code];

            $cards[] = [
                'code' => $code,
                'name' => $site->site_name,
                'category' => $site->category,
                'tree' => $tree,
                'routes' => array_map(
                    fn ($route) => array_map(fn ($node) => ['code' => $node['code'], 'name' => $node['name']], $route),
                    $routes,
                ),
                'routeCodes' => $routeCodes,
            ];

            array_push($allCodes, ...$routeCodes);
        }

        // two queries for every site on every route, not two per site
        $allCodes = array_values(array_unique($allCodes));
        $names = Site::query()->whereIn('site_code', $allCodes)->pluck('site_name', 'site_code')
            ->mapWithKeys(fn ($name, $c) => [strtoupper($c) => $name]);
        $batteries = Battery::query()->whereIn('site_code', $allCodes)
            ->orderBy('installation_date')
            ->get(['site_code', 'batteries_brand', 'no_strings', 'category', 'installation_date'])
            ->groupBy(fn ($b) => strtoupper($b->site_code));

        foreach ($cards as &$card) {
            $nodeNames = $this->nodeNames($card['tree']);

            $card['routeSites'] = array_map(fn ($c) => [
                'code' => $c,
                // the plain site name; the sheet's spelling ("... XPIC") when the site is not in the table
                'name' => $names[$c] ?? $nodeNames[$c] ?? null,
                'batteries' => ($batteries[$c] ?? collect())->map(fn ($b) => [
                    'brand' => $b->batteries_brand,
                    'strings' => $b->no_strings,
                    'category' => $b->category,
                    'installed' => $b->installation_date ? substr((string) $b->installation_date, 0, 10) : null,
                ])->values()->all(),
            ], $card['routeCodes']);
        }
        unset($card);

        return $cards;
    }

    /** The rows of the Excel file, from the same cards the tab draws. */
    public function exportRows(): array
    {
        $rows = [];

        foreach ($this->cards() as $card) {
            $routes = $card['routes']
                ? implode("\n", array_map(
                    fn ($route) => implode(' <> ', array_map(fn ($n) => trim("{$n['code']} {$n['name']}"), $route)),
                    $card['routes'],
                ))
                : 'No live route';

            foreach ($card['routeSites'] as $site) {
                $batteries = $site['batteries'] ?: [null];   // a site without batteries still gets its row

                foreach ($batteries as $battery) {
                    $rows[] = [
                        $card['code'], $card['name'], $card['category'], $routes,
                        $site['code'], $site['name'],
                        $battery['brand'] ?? null, $battery['strings'] ?? null,
                        $battery['category'] ?? null, $battery['installed'] ?? null,
                    ];
                }
            }
        }

        return $rows;
    }

    /** Pre-order walk of the tree: hub first, each site once. */
    private function codesInOrder(array $nodes, array &$seen = []): array
    {
        foreach ($nodes as $node) {
            $seen[$node['code']] = true;
            $this->codesInOrder($node['children'], $seen);
        }

        return array_keys($seen);
    }

    private function nodeNames(array $nodes, array &$names = []): array
    {
        foreach ($nodes as $node) {
            $names[$node['code']] ??= $node['name'];
            $this->nodeNames($node['children'], $names);
        }

        return $names;
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex items-center gap-2 p-6 text-sm opacity-70">
                <span class="loading loading-spinner loading-sm"></span> Loading routes…
            </div>
            HTML;
    }

    public function render()
    {
        return view('livewire.sites.actions.site-routes-with-batteries', ['cards' => $this->cards()]);
    }
}
