<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Imports the legacy `action_modification` pivot (which actions each
 * modification involved). Run after legacy:import-modifications.
 *
 * Action ids are identical in both systems, but that is checked by name
 * rather than trusted. Pivot ids are NOT preserved: rows created in this app
 * already occupy low ids, and nothing references a pivot id anyway. Rows are
 * matched on the (modification_id, action_id) pair instead.
 *
 * Idempotent and additive: pairs already present are left alone, and actions
 * attached in this app since are never removed.
 */
class ImportLegacyModificationActions extends Command
{
    protected $signature = 'legacy:import-modification-actions
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Import the action_modification pivot from the legacy database';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $legacy = DB::connection('legacy');

        $this->line('target: ' . DB::connection()->getDatabaseName()
            . '   source: ' . $legacy->getDatabaseName());

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $this->assertActionMapping();

        $modifications = DB::table('modifications')->pluck('id')->flip();
        $existing = DB::table('action_modification')
            ->get(['modification_id', 'action_id'])
            ->mapWithKeys(fn ($r) => ["{$r->modification_id}-{$r->action_id}" => true]);

        $payload = [];
        $skipped = [];
        $present = 0;
        $duplicates = 0;

        foreach ($legacy->table('action_modification')->orderBy('id')->get() as $r) {
            $pair = "{$r->modification_id}-{$r->action_id}";

            if (! isset($modifications[$r->modification_id])) {
                // dangling in legacy too: those modifications were hard-deleted there
                $skipped[] = "id={$r->id} modification_id={$r->modification_id}";

                continue;
            }

            if (isset($existing[$pair])) {
                $present++;

                continue;
            }

            if (isset($payload[$pair])) {
                $duplicates++;

                continue;
            }

            $payload[$pair] = [
                'modification_id' => $r->modification_id,
                'action_id' => $r->action_id,
                'created_at' => $this->ts($r->created_at),
                'updated_at' => $this->ts($r->updated_at),
            ];
        }

        $this->newLine();
        $this->line('action_modification');
        $this->line('    to insert: ' . count($payload));
        $this->line("    already present: {$present}");
        $this->line("    duplicate pairs in legacy: {$duplicates}");

        if ($skipped) {
            $this->line('    skipped (modification missing): ' . count($skipped));
            foreach (array_slice($skipped, 0, 10) as $s) {
                $this->line("      - {$s}");
            }
            if (count($skipped) > 10) {
                $this->line('      ... and ' . (count($skipped) - 10) . ' more');
            }
        }

        if (! $dry) {
            DB::transaction(function () use ($payload) {
                foreach (array_chunk(array_values($payload), 500) as $chunk) {
                    DB::table('action_modification')->insert($chunk);
                }
            });
        }

        $this->newLine();
        $this->info($dry ? 'dry run complete' : 'import complete');

        return self::SUCCESS;
    }

    /** Action ids are carried over as-is, so both tables must agree on them. */
    private function assertActionMapping(): void
    {
        $target = DB::table('actions')->pluck('name', 'id');

        foreach (DB::connection('legacy')->table('actions')->get(['id', 'name']) as $row) {
            if (($target[$row->id] ?? null) !== $row->name) {
                throw new \RuntimeException(
                    "Legacy action id {$row->id} is '{$row->name}', but here it is '"
                    . ($target[$row->id] ?? 'missing') . "'. Action ids can no longer be copied as-is."
                );
            }
        }

        $this->line('  action id mapping verified against legacy.actions');
    }

    /** Legacy uses '0000-00-00 00:00:00' as a null date. */
    private function ts(?string $value): ?string
    {
        if (! $value || str_starts_with($value, '0000-')) {
            return null;
        }

        return $value;
    }
}
