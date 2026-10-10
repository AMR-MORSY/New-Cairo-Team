<?php

namespace App\Console\Commands;

use App\Enums\ModificationStatus;
use App\Enums\Zones;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports sites, users and modifications from the cairo-team.com dump
 * (the `legacy` connection) into the current schema.
 *
 * Modifications depend on sites and users, so all three move together.
 * Primary keys are preserved throughout - legacy tables this import does not
 * touch still reference sites.site_code and users.id.
 *
 * Idempotent: re-running updates rows in place rather than duplicating them.
 */
class ImportLegacyModifications extends Command
{
    protected $signature = 'legacy:import-modifications
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Import sites, users and modifications from the legacy database';

    /** legacy sites.oz / modifications.oz -> Zones code */
    private const ZONE_CODES = [
        'CAIRO SOUTH' => 'CS',
        'CAIRO EAST' => 'CE',
        'CAIRO NORTH' => 'CN',
        'GIZA' => 'GZ',
        'NORTH UPPER' => 'NU',
        'SOUTH UPPER' => 'SU',
        'RED SEA' => 'RE',
        'SINAI' => 'SI',
        'DELTA NORTH' => 'DN',
        'DELTA SOUTH' => 'DS',
        'NORTH COAST' => 'NC',
        'ALEX' => 'AL',
    ];

    /** legacy modification_status.id -> enum */
    private const STATUS_IDS = [
        1 => 'Done',
        2 => 'In Progress',
        3 => 'Waiting D6',
        4 => 'Cancelled',
    ];

    private bool $dry = false;

    /** site codes prepared by importSites(), so a dry run can still validate. */
    private array $preparedSiteCodes = [];

    public function handle(): int
    {
        $this->dry = (bool) $this->option('dry-run');

        $this->line('target: ' . DB::connection()->getDatabaseName()
            . '   source: ' . DB::connection('legacy')->getDatabaseName());

        if ($this->dry) {
            $this->warn('dry run - nothing will be written');
        }

        $this->assertStatusMapping();

        $this->importSites();
        $this->importUsers();
        $this->importModifications();

        $this->newLine();
        $this->info($this->dry ? 'dry run complete' : 'import complete');

        return self::SUCCESS;
    }

    /**
     * The status ids are only meaningful because both systems happen to seed
     * them in the same order. Verify rather than trust it.
     */
    private function assertStatusMapping(): void
    {
        foreach (DB::connection('legacy')->table('modification_status')->get() as $row) {
            $expected = self::STATUS_IDS[$row->id] ?? null;

            if ($expected !== $row->name) {
                throw new \RuntimeException(
                    "Legacy status id {$row->id} is '{$row->name}', expected '" . ($expected ?? 'nothing') . "'."
                    . ' The id mapping in this command is no longer valid.'
                );
            }
        }

        $this->line('  status id mapping verified against legacy.modification_status');
    }

    private function zoneFor(?string $oz): ?Zones
    {
        $code = self::ZONE_CODES[strtoupper(trim((string) $oz))] ?? null;

        return $code ? Zones::from($code) : null;
    }

    /* ------------------------------------------------------------ sites --- */

    private function importSites(): void
    {
        $this->newLine();
        $this->line('sites');

        $rows = DB::connection('legacy')->table('sites')->get();
        $payload = [];
        $skipped = [];

        foreach ($rows as $r) {
            $zone = $this->zoneFor($r->oz);

            if (! $zone) {
                $skipped[] = $r->site_code . ' (zone "' . $r->oz . '")';

                continue;
            }

            // legacy `zone` holds the team; it is null on 85 rows, and the
            // zone determines the team anyway.
            $payload[] = [
                'site_code' => strtoupper(trim($r->site_code)),
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
                'cells_2G' => $r->{'2G_cells'} ?? 0,
                'cells_3G' => $r->{'3G_cells'} ?? 0,
                'cells_4G' => $r->{'4G_cells'} ?? 0,
                'status' => $r->status ?: 'On Air',
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];

            $this->preparedSiteCodes[strtoupper(trim($r->site_code))] = strtoupper(trim($r->site_code));
        }

        $this->report('sites', count($payload), $skipped);

        if (! $this->dry) {
            foreach (array_chunk($payload, 500) as $chunk) {
                DB::table('sites')->upsert($chunk, ['site_code']);
            }
        }
    }

    /* ------------------------------------------------------------ users --- */

    private function importUsers(): void
    {
        $this->newLine();
        $this->line('users');

        $rows = DB::connection('legacy')->table('users')->get();
        $payload = [];

        foreach ($rows as $r) {
            $payload[] = [
                'id' => $r->id,                 // preserved - modifications.action_owner points here
                'name' => $r->name,
                'email' => $r->email,
                'email_verified_at' => $this->ts($r->email_verified_at),
                'password' => $r->password,     // bcrypt hashes carry over intact
                'remember_token' => $r->remember_token,
                'time_zone' => $r->timezone,    // renamed column
                'login_at' => $this->ts($r->login_at),
                'team' => null,                 // assigned by hand afterwards
                'zone' => null,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

        $this->report('users', count($payload), []);

        if (! $this->dry) {
            DB::table('users')->upsert($payload, ['id']);
        }
    }

    /* ---------------------------------------------------- modifications --- */

    private function importModifications(): void
    {
        $this->newLine();
        $this->line('modifications');

        $siteCodes = DB::table('sites')->pluck('site_code')
            ->mapWithKeys(fn ($c) => [strtoupper(trim($c)) => $c])
            ->union($this->preparedSiteCodes);

        $rows = DB::connection('legacy')->table('modifications')->get();
        $payload = [];
        $skipped = [];
        $noProject = 0;

        foreach ($rows as $r) {
            $code = strtoupper(trim($r->site_code));

            if (! isset($siteCodes[$code])) {
                $skipped[] = "id={$r->id} site_code={$r->site_code}";

                continue;
            }

            $zone = $this->zoneFor($r->oz);

            if (! $zone) {
                $skipped[] = "id={$r->id} zone=\"{$r->oz}\"";

                continue;
            }

            if (! $r->project) {
                $noProject++;
            }

            $payload[] = [
                'id' => $r->id,
                'site_code' => $siteCodes[$code],
                'subcontractor_id' => $r->subcontractor,
                'requester_id' => $r->requester,
                'description' => $r->description,
                'pending' => $r->pending,
                'status' => self::STATUS_IDS[$r->status],
                'zone' => $zone->value,
                'team' => $zone->team()->value,   // legacy modifications carry no team
                'action_owner' => $r->action_owner,
                'project_id' => $r->project ?: null,   // legacy 0 means "no project"
                'request_date' => $r->request_date,
                'd6_date' => $r->d6_date,
                'cw_date' => $r->cw_date,
                'wo_code' => $r->wo_code,
                'final_cost' => $r->final_cost ?? 0,
                'est_cost' => $r->est_cost,
                'reported' => (int) $r->reported === 1,   // 1=Yes, 2=No
                'reported_at' => $r->reported_at,
                'deleted_at' => $this->ts($r->deleted_at),
                'month' => $r->month,
                'year' => $r->year,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

        $this->report('modifications', count($payload), $skipped);
        $this->line("    project_id set to NULL (legacy project = 0): {$noProject}");

        if (! $this->dry) {
            // the model's creating hook would overwrite wo_code, so write directly
            foreach (array_chunk($payload, 500) as $chunk) {
                DB::table('modifications')->upsert($chunk, ['id']);
            }
        }
    }

    /* ----------------------------------------------------------- helpers --- */

    /** Legacy uses '0000-00-00 00:00:00' as a null date. */
    private function ts(?string $value): ?string
    {
        if (! $value || str_starts_with($value, '0000-')) {
            return null;
        }

        return $value;
    }

    private function report(string $label, int $ok, array $skipped): void
    {
        $this->line("    ready: {$ok}");

        if ($skipped) {
            $this->line('    skipped: ' . count($skipped));
            foreach (array_slice($skipped, 0, 10) as $s) {
                $this->line("      - {$s}");
            }
            if (count($skipped) > 10) {
                $this->line('      ... and ' . (count($skipped) - 10) . ' more');
            }
        }
    }
}
