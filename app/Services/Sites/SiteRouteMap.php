<?php

namespace App\Services\Sites;

use App\Models\Site\SiteRoute;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Answers the two questions the planning sheet exists to answer:
 *
 *   routesTo()      how this site gets back to a hub, by every drawn route
 *   cascadesUnder() what hangs off it, and would go down with it
 *
 * Routes come from the `path` recorded against each hop, which is the chain
 * the planner drew on that row. They are not rebuilt by following parent
 * links: a handful of sites have two parents, and walking those yields every
 * combination of them -- 7188CA produces 19 paths that way, most of which
 * were never built. Reading drawn paths keeps the answer to what the sheet
 * actually says, and cannot loop.
 */
class SiteRouteMap
{
    private ?Collection $rows = null;

    private ?array $nameMap = null;

    /** child_code => the first hop naming it; firstWhere() over every hop was the cost of routesTo(). */
    private ?array $firstHopByChild = null;

    public function __construct(private readonly ?string $source = null) {}

    /**
     * Every distinct route that reaches $code, shortest first.
     *
     * @return array<int, array<int, array{code: string, name: ?string, mmu_near: ?string, mmu_far: ?string}>>
     */
    public function routesTo(string $code): array
    {
        return $this->describeRoutes($this->routeEntries($code));
    }

    /**
     * routesTo() without the routes the planners have struck out: a route is
     * dropped when every row drawing it has a red cell somewhere along it.
     * Judged on each row's own cells - describe() takes a node's fill from
     * the first row naming that site, and 3531CA is red on one row but
     * yellow on another.
     */
    public function liveRoutesTo(string $code): array
    {
        return $this->describeRoutes(array_filter($this->routeEntries($code), fn ($entry) => $entry[2]));
    }

    /**
     * liveRoutesTo() merged on shared parents, one root per hub:
     * [{code, name, children: [...]}]. A run with no split is a chain of
     * single children; where routes part, the parent holds each branch.
     *
     * @return array<int, array{code: string, name: ?string, children: array}>
     */
    public function routeTree(string $code): array
    {
        return $this->treeFromRoutes($this->liveRoutesTo($code));
    }

    /** routeTree() for routes already in hand, so a caller needing both walks them once. */
    public function treeFromRoutes(array $routes): array
    {
        $roots = [];

        foreach ($routes as $route) {
            $level = &$roots;

            foreach ($route as $node) {
                $level[$node['code']] ??= ['code' => $node['code'], 'name' => $node['name'], 'children' => []];
                $level = &$level[$node['code']]['children'];
            }

            unset($level);
        }

        $list = function (array $nodes) use (&$list): array {
            return array_values(array_map(
                fn ($node) => ['code' => $node['code'], 'name' => $node['name'], 'children' => $list($node['children'])],
                $nodes,
            ));
        };

        return $list($roots);
    }

    /**
     * Every drawn path reaching $code, cut off there, as
     * key => [prefix, hop, live]. Taking the prefix matters for sites in the
     * middle of a chain: the row naming them as the child records one route,
     * longer rows running through them record others. One path can be drawn
     * on several rows; it is live when any of them is free of red up to here.
     *
     * @return array<string, array{0: array<int, string>, 1: SiteRoute, 2: bool}>
     */
    private function routeEntries(string $code): array
    {
        $code = $this->normalise($code);
        $seen = [];

        foreach ($this->load() as $hop) {
            $chain = explode('>', $hop->path);
            $at = array_search($code, $chain, true);

            if ($at === false) {
                continue;
            }

            $prefix = array_slice($chain, 0, $at + 1);

            if (count($prefix) < 2) {
                continue;   // the site is the hub here, which is not a route
            }

            $key = implode('>', $prefix);
            $live = ! in_array('red', array_slice(explode(',', (string) $hop->path_fills), 0, $at + 1), true);

            // keep the hop that actually names this site, for its MMU ids
            if (! isset($seen[$key]) || $hop->child_code === $code) {
                $seen[$key] = [$prefix, $hop, $live || ($seen[$key][2] ?? false)];
            } elseif ($live) {
                $seen[$key][2] = true;
            }
        }

        return $seen;
    }

    private function describeRoutes(array $entries): array
    {
        $routes = [];
        foreach ($entries as [$prefix, $hop]) {
            $routes[] = $this->describe($prefix, $hop);
        }

        usort($routes, fn ($a, $b) => count($a) <=> count($b));

        return $routes;
    }

    /**
     * Everything downstream of $code, as a tree.
     *
     * @return array<int, array{code: string, name: ?string, level: int, children: array}>
     */
    public function cascadesUnder(string $code): array
    {
        $code = $this->normalise($code);

        // every hop whose drawn path passes through this site
        $below = $this->load()->filter(function ($hop) use ($code) {
            $chain = explode('>', $hop->path);
            $at = array_search($code, $chain, true);

            return $at !== false && $at < count($chain) - 1;
        });

        return $this->nest($below, $code);
    }

    /**
     * Flat list of every distinct site downstream of $code.
     *
     * Red cells are sites the planners have removed or rerouted, so a row is
     * ignored when $code or anything below it on that row is red: whatever
     * hangs off a red cell goes with it. A site drawn red on one row and
     * uncoloured or yellow on another still counts through the other row.
     */
    public function cascadedCodes(string $code): array
    {
        $code = $this->normalise($code);

        return $this->load()
            ->filter(function ($hop) use ($code) {
                $chain = explode('>', $hop->path);
                $at = array_search($code, $chain, true);

                if ($at === false || $at >= count($chain) - 1) {
                    return false;
                }

                $fills = array_slice(explode(',', (string) $hop->path_fills), $at);

                return ! in_array('red', $fills, true) && $hop->fill !== 'red';
            })
            ->pluck('child_code')
            ->unique()
            ->reject(fn ($c) => $c === $code)
            ->values()
            ->all();
    }

    /**
     * count(cascadedCodes($code)) for every site at once, as code => count,
     * from a single pass over the hops - for a table that has to sort on it.
     * Codes with nothing live below them are left out (count 0).
     *
     * The rules are cascadedCodes()'s: a hop counts for a site when the site
     * sits above the hop's child (its first place in the path) and nothing
     * from there down to the child is red.
     *
     * @return array<string, int>
     */
    public function cascadeCounts(): array
    {
        $below = [];

        foreach ($this->load() as $hop) {
            if ($hop->fill === 'red') {
                continue;
            }

            $chain = explode('>', $hop->path);
            $fills = explode(',', (string) $hop->path_fills);
            $last = count($chain) - 1;

            // the highest place from which the path down to the child is free of red
            $clearFrom = $last;
            while ($clearFrom > 0 && ($fills[$clearFrom] ?? '') !== 'red' && ($fills[$clearFrom - 1] ?? '') !== 'red') {
                $clearFrom--;
            }

            if (($fills[$clearFrom] ?? '') === 'red') {
                continue;   // the child itself is red
            }

            $first = [];
            foreach ($chain as $i => $code) {
                $first[$code] ??= $i;
            }

            foreach ($first as $code => $i) {
                if ($i >= $clearFrom && $i < $last && $code !== $hop->child_code) {
                    $below[$code][$hop->child_code] = true;
                }
            }
        }

        return array_map('count', $below);
    }

    /**
     * Every nodal site: a code with a live link below it somewhere in the
     * sheets. A link drawn red at either end is not live - red cells are
     * sites the planners removed or rerouted, as in cascadedCodes().
     *
     * @return array<int, string>
     */
    public function nodalCodes(): array
    {
        $nodals = [];

        foreach ($this->load() as $hop) {
            $chain = explode('>', $hop->path);
            $fills = explode(',', (string) $hop->path_fills);
            $child = count($chain) - 1;

            if ($child < 1 || $chain[$child - 1] === $chain[$child]) {
                continue;   // a hub, or a site the sheet draws under itself (4509CA>4509CA) - not a cascade
            }

            if (($fills[$child - 1] ?? '') !== 'red' && ($fills[$child] ?? '') !== 'red') {
                $nodals[$this->normalise($chain[$child - 1])] = true;
            }
        }

        return array_keys($nodals);
    }

    /**
     * The sites $code hangs directly off, through live links only, as
     * code => name. More than one when the sheet draws alternate routes.
     *
     * @return array<string, ?string>
     */
    public function parentsOf(string $code): array
    {
        $code = $this->normalise($code);
        $parents = [];

        foreach ($this->load() as $hop) {
            $chain = explode('>', $hop->path);
            $at = array_search($code, $chain, true);

            if ($at === false || $at < 1) {
                continue;
            }

            $fills = explode(',', (string) $hop->path_fills);

            if (($fills[$at] ?? '') === 'red' || ($fills[$at - 1] ?? '') === 'red' || $chain[$at - 1] === $code) {
                continue;
            }

            $parents[$chain[$at - 1]] ??= $this->nameOf($chain[$at - 1]);
        }

        return $parents;
    }

    /** True when the sheet draws more than one way to reach this site. */
    public function hasAlternateRoutes(string $code): bool
    {
        return count($this->routesTo($code)) > 1;
    }

    /**
     * The subtree under $code laid out the way the sheet draws it: one table
     * row per leaf, with each parent spanning its descendants. Returns rows
     * of cells, each cell starting on the row it appears in.
     *
     * @return array<int, array<int, array{depth: int, rowspan: int, code: string, name: ?string, fill: ?string, mmu_near: ?string, mmu_far: ?string}>>
     */
    public function sheetGrid(string $code): array
    {
        return $this->buildGrid($this->rowsThrough($code));
    }

    /**
     * The same grid, but cut into one per route that reaches $code.
     *
     * The single grid merges every route into one picture, which hides that
     * a site reached two ways is two separate drawings in the sheet. Rows
     * are grouped by the prefix that leads to the site, so each group is one
     * route and everything the planner drew beyond it.
     *
     * @return array<int, array{route: array<int, string>, grid: array}>
     */
    public function sheetGridsByRoute(string $code): array
    {
        $groups = [];

        foreach ($this->rowsThrough($code) as $row) {
            $key = implode('>', array_slice($row['chain'], 0, $row['at'] + 1));
            $groups[$key][] = $row;
        }

        // shortest route first, the order routesTo() reports them in
        uksort($groups, fn ($a, $b) => (substr_count($a, '>') <=> substr_count($b, '>')) ?: strcmp($a, $b));

        $out = [];

        foreach ($groups as $key => $rows) {
            $out[] = [
                'route' => explode('>', $key),
                'grid' => $this->buildGrid($rows),
            ];
        }

        return $out;
    }

    /**
     * Sheet rows whose chain runs through $code, each tagged with where.
     *
     * Every row that names this site is kept, including ones that stop
     * here. Those are not empty repeats: they are separate links the
     * planner drew, often from a different hub. 4167CA is drawn three
     * times, twice with no cascade of its own, and dropping those hid
     * two thirds of the picture.
     */
    private function rowsThrough(string $code): array
    {
        $code = $this->normalise($code);
        $kept = [];

        foreach ($this->sheetRows() as $row) {
            $at = array_search($code, $row['chain'], true);

            if ($at !== false) {
                $kept[] = $row + ['at' => $at];
            }
        }

        return $kept;
    }

    /** Lay a set of sheet rows out as merged cells. */
    private function buildGrid(array $kept): array
    {
        if (! $kept) {
            return [];
        }

        $kept = array_values($kept);
        $height = count($kept);
        $grid = array_fill(0, $height, []);

        $deepest = 0;
        foreach ($kept as $k) {
            $deepest = max($deepest, count($k['chain']) - 1);
        }

        /*
         * Render from column 0, not from the site: the sheet shows the whole
         * row, so the hub and everything between it and this site belong in
         * the picture. Column 0 is a hub, which is never a hop's child and so
         * is never "owned" -- it simply merges down while the code repeats.
         */
        for ($rel = 0; $rel <= $deepest; $rel++) {
            for ($i = 0; $i < $height; $i++) {
                $abs = $rel;
                $cellCode = $kept[$i]['chain'][$abs] ?? null;

                if ($cellCode === null) {
                    continue;
                }

                /*
                 * The sheet wrote a value in this cell only where a hop was
                 * recorded at that level; everywhere else the cell is blank
                 * because the one above is merged down over it. That is the
                 * merge information, recovered without storing ranges.
                 */
                $owns = isset($kept[$i]['own'][$abs]);
                $previous = $i > 0 ? ($kept[$i - 1]['chain'][$abs] ?? null) : null;

                if (! $owns && $previous === $cellCode) {
                    continue;   // covered by the block above
                }

                $span = 1;
                for ($j = $i + 1; $j < $height; $j++) {
                    if (($kept[$j]['chain'][$abs] ?? null) !== $cellCode
                        || isset($kept[$j]['own'][$abs])) {
                        break;
                    }

                    $span++;
                }

                $hop = $kept[$i]['own'][$abs] ?? null;

                $grid[$i][] = [
                    'depth' => $rel,
                    'rowspan' => $span,
                    'code' => $cellCode,
                    'name' => $kept[$i]['names'][$abs] ?? $this->nameOf($cellCode),
                    'fill' => $kept[$i]['fills'][$abs] ?? null,
                    'mmu_near' => $hop?->mmu_near,
                    'mmu_far' => $hop?->mmu_far,
                ];
            }
        }

        // keep each row's cells in column order
        foreach ($grid as $i => $cells) {
            usort($cells, fn ($a, $b) => $a['depth'] <=> $b['depth']);
            $grid[$i] = $cells;
        }

        return $grid;
    }

    /**
     * The sheet rebuilt row by row: the chain drawn on each row, and which
     * of its cells that row actually wrote rather than inherited.
     *
     * @return array<int, array{row: int, chain: array<int, string>, own: array<int, SiteRoute>}>
     */
    private function sheetRows(): array
    {
        $rows = [];

        foreach ($this->load() as $hop) {
            $r = (int) $hop->sheet_row;
            $chain = explode('>', $hop->path);

            $rows[$r]['row'] = $r;
            $rows[$r]['chain'] ??= [];

            if (count($chain) > count($rows[$r]['chain'])) {
                $rows[$r]['chain'] = $chain;
                $rows[$r]['fills'] = array_map(
                    fn ($f) => $f === '' ? null : $f,
                    explode(',', (string) $hop->path_fills)
                );
                $rows[$r]['names'] = $hop->path_names ?? [];
            }

            $rows[$r]['own'][(int) $hop->level] = $hop;
        }

        ksort($rows);

        return array_values(array_map(fn ($r) => $r + ['own' => [], 'fills' => [], 'names' => []], $rows));
    }

    /** The colour the planner gave this site, by any hop that names it. */
    public function fillOf(string $code): ?string
    {
        $code = $this->normalise($code);

        return $this->firstHopNaming($code)?->fill;
    }

    /**
     * True when the sheet draws this site at the head of a chain. A site can
     * be a hub and still hang off another hub elsewhere, so this is reported
     * alongside routesTo() rather than instead of it.
     */
    public function isHub(string $code): bool
    {
        return $this->load()->contains('hub_code', $this->normalise($code));
    }

    /** Every site the sheet treats as a hub. */
    public function hubs(): array
    {
        return $this->load()->pluck('hub_code')->unique()->sort()->values()->all();
    }

    /* ------------------------------------------------------------ internals */

    /** Name a site by any hop that records it as the child. */
    private function nameOf(string $code): ?string
    {
        return $this->names()[$code] ?? null;
    }

    private function names(): array
    {
        if ($this->nameMap !== null) {
            return $this->nameMap;
        }

        $this->nameMap = [];

        $keep = function (?string $code, ?string $name) {
            $name = (string) $name;

            if (! $code || $name === '') {
                return;
            }

            // prefer the plainest spelling; variants add " XPIC", " E-band"
            $held = $this->nameMap[$code] ?? null;

            if ($held === null || strlen($name) < strlen($held)) {
                $this->nameMap[$code] = $name;
            }
        };

        foreach ($this->load() as $hop) {
            $keep($hop->child_code, $hop->child_name);
            $keep($hop->hub_code, $hop->hub_name);
        }

        return $this->nameMap;
    }

    /** Turn a chain of codes into hops carrying names and MMU ids. */
    private function firstHopNaming(string $code): ?SiteRoute
    {
        if ($this->firstHopByChild === null) {
            $this->firstHopByChild = [];
            foreach ($this->load() as $hop) {
                $this->firstHopByChild[$hop->child_code] ??= $hop;
            }
        }

        return $this->firstHopByChild[$code] ?? null;
    }

    private function describe(array $chain, SiteRoute $leaf): array
    {
        $out = [];

        foreach ($chain as $i => $code) {
            // the supplied hop describes the last code only when it names it
            $hop = ($i === count($chain) - 1 && $leaf->child_code === $code)
                ? $leaf
                : $this->firstHopNaming($code);

            $out[] = [
                'code' => $code,
                'name' => $hop?->child_name ?: $this->nameOf($code),
                'mmu_near' => $hop?->mmu_near,
                'mmu_far' => $hop?->mmu_far,
                'fill' => $hop?->fill,
            ];
        }

        return $out;
    }

    /** Rebuild the subtree under $root from the paths that pass through it. */
    private function nest(Collection $hops, string $root): array
    {
        $childrenOf = [];

        foreach ($hops as $hop) {
            $chain = explode('>', $hop->path);
            $at = array_search($root, $chain, true);

            for ($i = $at; $i < count($chain) - 1; $i++) {
                $childrenOf[$chain[$i]][$chain[$i + 1]] = $hop;
            }
        }

        $build = function (string $code, array $seen) use (&$build, $childrenOf) {
            $seen[$code] = true;
            $out = [];

            foreach ($childrenOf[$code] ?? [] as $child => $hop) {
                if (isset($seen[$child])) {
                    continue;
                }

                $exact = $hop->child_code === $child;

                $out[] = [
                    'code' => $child,
                    'name' => $exact ? $hop->child_name : $this->nameOf($child),
                    'fill' => $exact ? $hop->fill : $this->fillOf($child),
                    'level' => $hop->level,
                    'mmu_near' => $exact ? $hop->mmu_near : null,
                    'mmu_far' => $exact ? $hop->mmu_far : null,
                    'children' => $build($child, $seen),
                ];
            }

            return $out;
        };

        return $build($root, []);
    }

    /** A few hundred rows, so one query and filter in memory. */
    private function load(): Collection
    {
        if ($this->rows !== null) {
            return $this->rows;
        }

        /*
         * No sheet has to be imported for the rest of the site page to work,
         * and a missing table would otherwise 500 every site view. The panel
         * has an empty state for exactly this.
         */
        if (! Schema::hasTable((new SiteRoute())->getTable())) {
            return $this->rows = collect();
        }

        return $this->rows = SiteRoute::query()
            ->when($this->source, fn ($q) => $q->where('source', $this->source))
            ->get();
    }

    private function normalise(string $code): string
    {
        return strtoupper(trim($code));
    }
}
