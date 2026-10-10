<?php

namespace App\Console\Commands;

use App\Models\Site\SheetImport;
use App\Models\Site\SiteRoute;
use App\Services\Sites\RouteSheetParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Loads a transmission planning sheet into `site_routes`.
 *
 * Idempotent per source: the rows for that source are replaced wholesale, so
 * re-running after the planner edits the sheet simply resyncs it.
 */
class ImportSiteRoutes extends Command
{
    protected $signature = 'routes:import-sheet
                            {path : Path to the .xlsx planning sheet}
                            {--source= : Name for this sheet (defaults to the file name)}
                            {--dry-run : Report what would change without writing}
                            {--if-changed : Skip unless the file has been edited since the last import}';

    protected $description = 'Import site transmission routes from a planning sheet';

    public function handle(RouteSheetParser $parser): int
    {
        $path = $this->argument('path');
        $source = $this->option('source') ?: Str::slug(pathinfo($path, PATHINFO_FILENAME));
        $dry = (bool) $this->option('dry-run');

        if (! is_readable($path)) {
            $this->error("Cannot read {$path}");

            return self::FAILURE;
        }

        $fingerprint = SheetImport::fingerprint($path);
        $last = SheetImport::query()->where('source', $source)->first();

        if ($this->option('if-changed') && $last && $last->fingerprint === $fingerprint) {
            // a stat() call, rather than 1.7s of reading the workbook
            $this->line("{$source}: unchanged since " . $last->imported_at?->diffForHumans());

            return self::SUCCESS;
        }

        $this->line("sheet : {$path}");
        $this->line("source: {$source}");
        $this->line('target: ' . DB::connection()->getDatabaseName());

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $hops = $parser->parse($path);

        if (! $hops) {
            $this->error('No hops found. Is this the expected sheet layout?');

            return self::FAILURE;
        }

        $this->report($hops, $parser->unparsed($path), $parser->layout($path));

        if ($dry) {
            $this->newLine();
            $this->info('dry run complete');

            return self::SUCCESS;
        }

        $now = now();
        $rows = array_map(fn ($h) => $h + ['source' => $source, 'created_at' => $now, 'updated_at' => $now], $hops);

        $unreadable = count($parser->unparsed($path));

        DB::transaction(function () use ($source, $rows, $path, $fingerprint, $unreadable, $hops) {
            SiteRoute::query()->where('source', $source)->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                SiteRoute::query()->insert($chunk);
            }

            SheetImport::query()->updateOrCreate(['source' => $source], [
                'path' => $path,
                'fingerprint' => $fingerprint,
                'hops' => count($rows),
                'sites' => count(array_unique(array_column($hops, 'child_code'))),
                'unreadable_cells' => $unreadable,
                'imported_at' => now(),
            ]);
        });

        $this->newLine();
        $this->info(count($rows) . " hops imported for '{$source}'");

        return self::SUCCESS;
    }

    private function report(array $hops, array $unparsed, array $layout): void
    {
        $children = array_column($hops, 'child_code');
        $hubs = array_unique(array_column($hops, 'hub_code'));

        $parentsPerChild = [];
        foreach ($hops as $h) {
            $parentsPerChild[$h['child_code']][$h['parent_code']] = true;
        }
        $multi = array_filter($parentsPerChild, fn ($p) => count($p) > 1);

        $levels = [];
        foreach ($hops as $h) {
            $levels[$h['level']] = ($levels[$h['level']] ?? 0) + 1;
        }
        ksort($levels);

        $this->newLine();

        // printed so a change to the sheet's shape is visible, not silent
        $this->line('  site columns         : ' . implode(', ', $layout['site_cols']));
        $this->line('  mmu columns          : ' . (
            $layout['mmu_cols']
                ? collect($layout['mmu_cols'])
                    ->map(fn ($pair, $site) => "col{$site}<-[" . implode(',', $pair) . ']')
                    ->implode('  ')
                : 'none'
        ));
        $this->line('  hops                 : ' . count($hops));
        $this->line('  distinct sites       : ' . count(array_unique($children)));
        $this->line('  hubs                 : ' . count($hubs));
        $this->line('  sites on >1 route    : ' . count($multi));
        $this->line('  hops carrying MMU ids: ' . count(array_filter($hops, fn ($h) => $h['mmu_near'] || $h['mmu_far'])));

        $this->line('  by level             : ' . collect($levels)->map(fn ($n, $l) => "L{$l}={$n}")->implode('  '));

        if ($unparsed) {
            $this->newLine();
            $this->warn('  cells with no readable site code: ' . count($unparsed));
            foreach (array_slice($unparsed, 0, 10) as $u) {
                $this->line("    row {$u['row']}  \"{$u['value']}\"");
            }
            if (count($unparsed) > 10) {
                $this->line('    ... and ' . (count($unparsed) - 10) . ' more');
            }
        }
    }
}
