<?php

namespace App\Console\Commands;

use App\Enums\Zones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Copies sites that exist in the old website's database but not in this one.
 *
 * The source is the previous version of the app (the `new_orange` database),
 * which stores zone and team as ids into its own `zones` / `teams` tables.
 * Here they are enum codes, so the zone id is translated through the source's
 * zones table by name, and the team follows from the zone.
 *
 * Additive only: sites already present here are never touched, so edits made
 * in this app survive. Matching is on site_code, case- and space-insensitive.
 * Source ids are not kept - everything references sites by site_code.
 */
class SyncMissingSites extends Command
{
    protected $signature = 'legacy:sync-missing-sites
                            {--connection=legacy : Database connection holding the old website\'s data}
                            {--dry-run : List the missing sites without writing}';

    protected $description = 'Copy sites found in the old website\'s database but missing from this one';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $source = DB::connection($this->option('connection'));

        $this->line('target: ' . DB::connection()->getDatabaseName()
            . '   source: ' . $source->getDatabaseName());

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $zones = $this->zoneMap($source);

        $existing = DB::table('sites')->pluck('site_code')
            ->mapWithKeys(fn ($c) => [$this->normalise($c) => true]);

        $payload = [];
        $skipped = [];

        foreach ($source->table('sites')->orderBy('site_code')->get() as $r) {
            $code = $this->normalise($r->site_code);

            if (isset($existing[$code]) || isset($payload[$code])) {
                continue;
            }

            $zone = $zones[$r->zone_id] ?? null;

            if (! $zone) {
                $skipped[] = "{$code} (zone_id {$r->zone_id})";

                continue;
            }

            $payload[$code] = [
                'site_code' => $code,
                'site_name' => $r->site_name,
                'BSC' => $r->BSC,
                'RNC' => $r->RNC,
                'office' => $r->office,
                'type' => $r->type,
                'category' => $r->category,
                'severity' => $r->severity,
                'sharing' => $r->sharing,
                'host' => $r->host,
                'gest' => $r->gest,
                'vf_code' => $r->vf_code,
                'et_code' => $r->et_code,
                'we_code' => $r->we_code,
                'zone' => $zone->value,
                'team' => $zone->team()->value,
                'cells_2G' => $r->cells_2G ?? 0,
                'cells_3G' => $r->cells_3G ?? 0,
                'cells_4G' => $r->cells_4G ?? 0,
                'status' => $r->status ?: 'On Air',
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

        $this->newLine();
        $this->line('sites missing here: ' . count($payload));

        if ($payload) {
            $this->table(
                ['site_code', 'site_name', 'zone', 'status'],
                array_map(fn ($s) => [$s['site_code'], $s['site_name'], $s['zone'], $s['status']], array_values($payload))
            );
        }

        if ($skipped) {
            $this->warn('skipped, zone not recognised: ' . count($skipped));
            foreach ($skipped as $s) {
                $this->line("  - {$s}");
            }
        }

        if (! $dry && $payload) {
            DB::transaction(function () use ($payload) {
                foreach (array_chunk(array_values($payload), 500) as $chunk) {
                    DB::table('sites')->insert($chunk);
                }
            });
        }

        $this->newLine();
        $this->info($dry ? 'dry run complete' : count($payload) . ' sites copied');

        return self::SUCCESS;
    }

    /**
     * Source zone id => Zones case, read from the source's own zones table
     * rather than assuming its ids.
     *
     * @return array<int, Zones>
     */
    private function zoneMap($source): array
    {
        $byLabel = collect(Zones::cases())->keyBy(fn (Zones $z) => strtolower($z->label()));
        $map = [];

        foreach ($source->table('zones')->get(['id', 'name']) as $row) {
            $zone = $byLabel[strtolower(trim($row->name))] ?? null;

            if (! $zone) {
                $this->warn("  source zone {$row->id} '{$row->name}' has no match here");

                continue;
            }

            $map[$row->id] = $zone;
        }

        return $map;
    }

    private function normalise(string $code): string
    {
        return strtoupper(trim($code));
    }

    /** Old dumps use '0000-00-00 00:00:00' as a null date. */
    private function ts(?string $value): ?string
    {
        if (! $value || str_starts_with($value, '0000-')) {
            return null;
        }

        return $value;
    }
}
