<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Converts site_notices.notice_type (string) into the site_notice_type_id
 * foreign key, on a database created before the migration was changed.
 *
 * notice_type holds either an id of site_notice_types ('1'..'6') or a name
 * from it ('Microwave'). Numbers are kept, names become their id, then the
 * column is renamed, made an unsigned bigint and constrained.
 *
 * Works on the raw tables, not the models: the models describe the schema
 * after this runs, not before. Safe to re-run - once site_notice_type_id
 * exists there is nothing left to do.
 */
class MapSiteNoticeTypesColumn extends Command
{
    protected $signature = 'site-notices-table:map-site-notice-types-column
                            {--dry-run : List the changes without writing}';

    protected $description = 'Turn site_notices.notice_type into the site_notice_type_id foreign key';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $this->line('target: ' . DB::connection()->getDatabaseName());

        if (Schema::hasColumn('site_notices', 'site_notice_type_id')) {
            $this->info('site_notices.site_notice_type_id already exists - nothing to do');

            return self::SUCCESS;
        }

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $types = DB::table('site_notice_types')->pluck('name', 'id');   // id => name

        if ($types->isEmpty()) {
            $this->error('site_notice_types is empty - seed it first (SiteNoticeTypesSeeder)');

            return self::FAILURE;
        }

        $idByName = $types->mapWithKeys(fn ($name, $id) => [strtolower(trim($name)) => (int) $id]);

        $values = DB::table('site_notices')
            ->select('notice_type', DB::raw('count(*) as notices'))
            ->groupBy('notice_type')
            ->orderBy('notice_type')
            ->get();

        $renames = [];     // name => id
        $unmatched = [];

        foreach ($values as $row) {
            $value = trim((string) $row->notice_type);

            if (ctype_digit($value)) {
                // already an id - keep it, but it must exist for the foreign key
                if (! isset($types[(int) $value])) {
                    $unmatched[] = "'{$value}' is not a site_notice_types id ({$row->notices} notices)";
                } else {
                    $this->line(sprintf('  %-16s kept      %4d notices', "'{$value}'", $row->notices));
                }

                continue;
            }

            $id = $idByName[strtolower($value)] ?? null;

            if ($id === null) {
                $unmatched[] = "'{$value}' matches no site_notice_types name ({$row->notices} notices)";

                continue;
            }

            $renames[(string) $row->notice_type] = $id;
            $this->line(sprintf('  %-16s -> id %-4d %4d notices', "'{$value}'", $id, $row->notices));
        }

        if ($unmatched) {
            $this->newLine();
            $this->error('cannot convert - fix these values first:');
            foreach ($unmatched as $u) {
                $this->line("  {$u}");
            }

            // all or nothing: a half-mapped column cannot become a foreign key
            return self::FAILURE;
        }

        if ($dry) {
            $this->newLine();
            $this->line('then: rename notice_type -> site_notice_type_id, unsigned bigint, foreign key to site_notice_types.id');
            $this->info('dry run complete');

            return self::SUCCESS;
        }

        // 1. names -> ids
        DB::transaction(function () use ($renames) {
            foreach ($renames as $name => $id) {
                DB::table('site_notices')->where('notice_type', (string) $name)->update(['notice_type' => (string) $id]);
            }
        });
        $this->info('  names replaced with ids');

        // 2. schema. MySQL commits DDL implicitly, so these run outside the transaction.
        Schema::table('site_notices', function (Blueprint $table) {
            $table->renameColumn('notice_type', 'site_notice_type_id');
        });

        Schema::table('site_notices', function (Blueprint $table) {
            $table->unsignedBigInteger('site_notice_type_id')->change();
            $table->foreign('site_notice_type_id')->references('id')->on('site_notice_types')->onDelete('cascade');
        });
        $this->info('  notice_type is now site_notice_type_id, a foreign key to site_notice_types');

        $this->newLine();
        $this->info('conversion complete');

        return self::SUCCESS;
    }
}
