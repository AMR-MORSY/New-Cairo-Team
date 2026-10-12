<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSiteEquipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    /**
     * The console command description.
     *
     * @var string
     */

    private bool $dry = false;

    /** tables held back because some rows reference sites missing from `sites` */
    private array $notLoaded = [];
    protected $signature = 'legacy:import-site-equipment
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Import site deep data, batteries, power data, images and notes from the legacy database';


    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->dry = (bool) $this->option('dry-run');

        $this->line('target: ' . DB::connection()->getDatabaseName()
            . '   source: ' . DB::connection('legacy')->getDatabaseName());

        if ($this->dry) {
            $this->warn('dry run - nothing will be written');
        }
        // $this->importSiteData();
        $this->importSiteBatteries();
        $this->importSitePower();
        $this->importSiteNotices();

        $this->newLine();
        if ($this->notLoaded) {
            $this->error('not loaded: ' . implode(', ', $this->notLoaded)
                . ' - add the missing sites, then run again');

            return self::FAILURE;
        }

        $this->info($this->dry ? 'dry run complete' : 'import complete');

        return self::SUCCESS;
    }


    private function importSiteData(): void
    {
        $this->newLine();
        $this->line('site_data');


        $existing = $this->existingSiteCodes();

        $payload = [];
        $skipped = [];

        foreach (DB::connection('legacy')->table('site_data')->orderBy('site_code')->get() as $r) {
            $code = $this->normalise($r->site_code);

            if (!isset($existing[$code])) {
                $skipped[] = "{$code} (not in sites table)";
                continue;
            }
            $payload[$r->id] = [
                'id' => $r->id,                 // preserved - modifications.action_owner points here
                'site_code' => strtoupper(trim($r->site_code)),
                'on_air_date' => $this->ts($r->on_air_date),
                'topology' => $r->topology,
                'structure' => $r->structure,     // bcrypt hashes carry over intact
                'equip_room' => $r->equip_room,
                'address' => $r->address,    // renamed column
                'x_coordinate' => $r->x_coordinate,
                'y_coordinate' => $r->y_coordinate,
                'ntra_cluster' => $r->ntra_cluster,                 // assigned by hand afterwards
                'care_ceo' => $r->care_ceo,
                'axis' => $r->axis,
                "serve_compound" => $r->serve_compound,
                "universities" => $r->universities,
                "hot_spot" => $r->hot_spot,
                "network_type" => $r->network_type,
                'last_pm_date' => $this->ts($r->last_pm_date),
                "need_access_permission" => $r->need_access_permission,
                "permission_type" => $r->permission_type,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }




        $this->report('site_data', count($payload), $skipped);

        // all or nothing: a table is only loaded when every row's site exists
        if ($skipped) {
            $this->warn('    loading the other ' . count($payload) . ' rows; the skipped ones are left out');

            
        }

        if (! $this->dry) {
            // the model's creating hook would overwrite wo_code, so write directly
            foreach (array_chunk(array_values($payload), 500) as $chunk) {
                DB::table('site_data')->upsert($chunk, ['id']);
            }
        }
    }

    private function importSiteBatteries(): void
    {
        $this->newLine();
        $this->line('batteries');

        $existing = $this->existingSiteCodes();
        $skipped = [];
        $payload = [];

        foreach (DB::connection('legacy')->table('batteries')->orderBy('site_code')->get() as $r) {
            $code = $this->normalise($r->site_code);

            if (!isset($existing[$code])) {
                $skipped[] = "{$code} (not in sites table)";
                continue;
            }
            $payload[$r->id] = [
                'id' => $r->id,               
                'site_code' => strtoupper(trim($r->site_code)),
                'batteries_brand' => $r->batteries_brand,
                'installation_date' => $this->ts($r->installation_date),
                'no_strings' => $r->no_strings,
                "category" => $r->category,
                "stock" => $r->stock,
                "comment" => $r->comment,
                "theft_case" => $this->ts($r->theft_case),
                "batteries_status" => $r->batteries_status,
                "battery_volt" => $r->battery_volt,
                "battery_amp_hr" => $r->battery_amp_hr,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }
        

        $this->report('batteries', count($payload), $skipped);

        // all or nothing: a table is only loaded when every row's site exists
        if ($skipped) {
             $this->warn('    loading the other ' . count($payload) . ' rows; the skipped ones are left out');

            // return;
        }

        if (! $this->dry) {
            // the model's creating hook would overwrite wo_code, so write directly
            foreach (array_chunk(array_values($payload), 500) as $chunk) {
                DB::table('batteries')->upsert($chunk, ['id']);
            }
        }
    }

    private function importSitePower(): void
    {
        $this->newLine();
        $this->line('site_power_data');

        $existing = $this->existingSiteCodes();
        $skipped = [];
        $payload = [];

        foreach (DB::connection('legacy')->table('site_power_data')->orderBy('site_code')->get() as $r) {
            $code = $this->normalise($r->site_code);

            if (!isset($existing[$code])) {
                $skipped[] = "{$code} (not in sites table)";
                continue;
            }
             $payload[$r->id] = [
                'id' => $r->id,                 // preserved - modifications.action_owner points here
                'site_code' => strtoupper(trim($r->site_code)),
                'power_source' => $r->power_source,
                'power_meter_type' => $r->power_meter_type,
                'gen_config' => $r->gen_config,
                'gen_serial' => $r->gen_serial,
                'gen_capacity' => $r->gen_capacity,
                "overhaul_consumption" => $r->overhaul_consumption,
                "c_length" => $r->c_length,
                "c_size" => $r->c_size,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

        $this->report('site_power_data', count($payload), $skipped);

        // all or nothing: a table is only loaded when every row's site exists
        if ($skipped) {
          $this->warn('    loading the other ' . count($payload) . ' rows; the skipped ones are left out');
          
        }

         if (! $this->dry) {
            // the model's creating hook would overwrite wo_code, so write directly
            foreach (array_chunk(array_values($payload), 500) as $chunk) {
                DB::table('site_power_data')->upsert($chunk, ['id']);
            }
        }
    }

    private function importSiteNotices(): void
    {
        $this->newLine();
        $this->line('site_notices');

         $existing = $this->existingSiteCodes();
        $skipped = [];
        $payload = [];

        foreach (DB::connection('legacy')->table('site_notices')->orderBy('site_code')->get() as $r) {
            $code = $this->normalise($r->site_code);

            if (!isset($existing[$code])) {
                $skipped[] = "{$code} (not in sites table)";
                continue;
            }
             $payload[$r->id] = [
                'id' => $r->id,                 // preserved - modifications.action_owner points here
                'site_code' => strtoupper(trim($r->site_code)),
                'notice_type' => $r->notice_type,
                "title" => $r->title,
                "description" => $r->description,
                "is_solved" => $r->is_solved,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

      

        $this->report('site_notices', count($payload), $skipped);

        // all or nothing: a table is only loaded when every row's site exists
        if ($skipped) {
           $this->warn('    loading the other ' . count($payload) . ' rows; the skipped ones are left out');

           
        }

        if (! $this->dry) {


            DB::table('site_notices')->upsert(array_values($payload), ['id']);
        }
    }


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

    // private function blocked(string $label, array $skipped): void
    // {
    //     $this->notLoaded[] = $label;

    //     $codes = array_unique(array_map(fn ($s) => strtok($s, ' '), $skipped));

    //     $this->error("    {$label} NOT loaded: " . count($skipped) . ' rows reference '
    //         . count($codes) . ' site codes missing from the sites table');
    //     $this->line('    missing: ' . implode(', ', $codes));
    // }

    private function normalise(string $code): string
    {
        return strtoupper(trim($code));
    }


    private function existingSiteCodes(): array
    {
        return  DB::table('sites')->pluck('site_code')
            ->mapWithKeys(fn($c) => [$this->normalise($c) => true])->toArray();
    }
}
