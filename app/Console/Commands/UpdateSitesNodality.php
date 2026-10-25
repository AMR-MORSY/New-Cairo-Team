<?php

namespace App\Console\Commands;

use App\Enums\SiteCategories;
use App\Models\Site\Site;
use App\Services\Sites\SiteRouteMap;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Makes site_routes, which ImportSiteRoutes fills from the planning sheets,
 * the only source of nodality. Routes change, so this both adds and removes
 * NDL across the whole sites table:
 *
 *   - every site first drops NDL ("TP + NDL" -> "TP"),
 *   - then each parent in site_routes gains it back via mapSiteNodality().
 *
 * So a site in a zone with no imported sheet ends up non-nodal. Both steps
 * are worked out together and only the sites whose category actually
 * changes are written: the same result as downgrading and re-upgrading,
 * without rewriting every nodal site twice.
 *
 * A red cell is a site the planners removed or rerouted (see
 * SiteRouteMap::cascadedCodes), so a link drawn red does not make its parent
 * a nodal.
 */
class UpdateSitesNodality extends Command
{
    protected $signature = 'update:sites-nodality {--dry-run : Report what would change without writing}';

    protected $description = 'Reset NDL on every site, then add it back to each parent in site_routes.';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $hops = DB::table('site_routes')->get(['path', 'path_fills']);

        if ($hops->isEmpty()) {
            // with no routes every site would look like a non-nodal and lose NDL
            $this->error('site_routes is empty - import the planning sheets first (routes:import-sheet).');

            return self::FAILURE;
        }

        // code => true; the same definition the nodal sites table uses
        $parents = array_fill_keys((new SiteRouteMap())->nodalCodes(), true);

        // every site: the parents in site_routes are the nodals, everything else is not
        $sites = Site::query()->get(['id', 'site_code', 'site_name', 'category']);

        $changes = [];
        $invalid = [];
        $unchanged = 0;

        foreach ($sites as $site) {
            $code = strtoupper(trim($site->site_code));
            $base = SiteCategories::removeNodality($site->category);

            if ($base === null) {
                $invalid[] = [$site->site_code, $site->site_name, $site->category];

                continue;
            }

            $target = isset($parents[$code]) ? SiteCategories::mapSiteNodality($base) : $base;

            if ($target === $site->category) {
                $unchanged++;

                continue;
            }

            $changes[] = ['site' => $site, 'from' => $site->category, 'to' => $target, 'nodal' => isset($parents[$code])];
        }

        $gained = array_filter($changes, fn ($c) => $c['nodal']);
        $lost = array_filter($changes, fn ($c) => ! $c['nodal']);

        foreach (['Becoming nodal (NDL added)' => $gained, 'No longer nodal (NDL removed)' => $lost] as $title => $group) {
            if ($group) {
                $this->newLine();
                $this->line($title . ': ' . count($group));
                $this->table(
                    ['Site', 'Name', 'From', 'To'],
                    array_map(fn ($c) => [$c['site']->site_code, $c['site']->site_name, $c['from'], $c['to']], $group)
                );
            }
        }

        if (! $dry && $changes) {
            DB::transaction(function () use ($changes) {
                foreach ($changes as $change) {
                    $change['site']->update(['category' => $change['to']]);
                }
            });
        }

        if ($invalid) {
            $this->warn('Sites skipped - their category is not one of SiteCategories:');
            $this->table(['Site', 'Name', 'Category'], $invalid);
        }

        $notFound = array_diff(array_keys($parents), $sites->map(fn ($s) => strtoupper(trim($s->site_code)))->all());

        if ($notFound) {
            $this->warn('Parents in site_routes with no site in the sites table (' . count($notFound) . '):');
            $this->line(implode(', ', $notFound));
        }

        $this->newLine();
        $this->info(sprintf(
            '%d sites, %d parents in site_routes: %d %s NDL, %d %s NDL, %d unchanged, %d skipped.',
            $sites->count(),
            count($parents),
            count($gained),
            $dry ? 'would gain' : 'gained',
            count($lost),
            $dry ? 'would lose' : 'lost',
            $unchanged,
            count($invalid),
        ));

        return self::SUCCESS;
    }

}
