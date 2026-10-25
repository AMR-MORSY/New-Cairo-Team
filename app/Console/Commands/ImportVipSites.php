<?php

namespace App\Console\Commands;

use App\Enums\SiteCategories;
use App\Models\Site\Site;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Makes the yearly VIP list the only source of VIP in site categories:
 *
 *   - every site first drops VIP ("TP + VIP" -> "TP") via downgradeFromVip(),
 *   - then each site on the list gains it back via upgradeToVip().
 *
 * The list is read straight from the workbook, with no table of its own. Both
 * steps are worked out together and only sites whose category actually
 * changes are written, in one transaction: the same result as downgrading
 * and re-upgrading, without rewriting every VIP site twice.
 *
 * Codes on the list with no site are reported and saved to a CSV, so they can
 * be added and the import run again.
 */
class ImportVipSites extends Command
{
    protected $signature = 'sites:import-vip
                            {path=VIP list 2026.xlsx : Path to the VIP list workbook}
                            {--column=Site Code : Header of the column holding the site codes}
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Reset VIP on every site, then add it back to the sites on the VIP list workbook.';

    public function handle(): int
    {
        $path = $this->argument('path');
        $dry = (bool) $this->option('dry-run');

        if (! is_readable($path)) {
            $this->error("Cannot read {$path}");

            return self::FAILURE;
        }

        $list = $this->readList($path);

        if ($list === null) {
            return self::FAILURE;
        }

        $this->line("sheet : {$path}");
        $this->line('target: ' . DB::connection()->getDatabaseName());
        $this->line('codes on the list: ' . count($list));

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        $sites = Site::query()->get(['id', 'site_code', 'site_name', 'category'])
            ->keyBy(fn ($s) => strtoupper(trim($s->site_code)));

        $notFound = array_diff_key($list, $sites->all());

        if (count($notFound) === count($list)) {
            // nothing matched: carrying on would strip VIP from every site
            $this->error('None of the codes on the list is in the sites table - is this the right file and column?');

            return self::FAILURE;
        }

        $gained = [];
        $lost = [];
        $noVariant = [];
        $invalid = [];
        $unchanged = 0;

        foreach ($sites as $code => $site) {
            $base = SiteCategories::downgradeFromVip($site->category);

            if ($base === null) {
                $invalid[] = [$site->site_code, $site->site_name, $site->category];

                continue;
            }

            $vip = isset($list[$code]);
            $target = $vip ? SiteCategories::upgradeToVip($base) : $base;

            if ($vip && ! str_contains($target, 'VIP')) {
                // LDN, BSC: on the list, but the category has no VIP variant
                $noVariant[] = [$site->site_code, $site->site_name, $site->category, $list[$code]];
            }

            if ($target === $site->category) {
                $unchanged++;

                continue;
            }

            $change = ['site' => $site, 'from' => $site->category, 'to' => $target];
            $vip ? $gained[] = $change : $lost[] = $change;
        }

        foreach (['Becoming VIP' => $gained, 'No longer VIP' => $lost] as $title => $group) {
            if ($group) {
                $this->newLine();
                $this->line("{$title}: " . count($group));
                $this->table(
                    ['Site', 'Name', 'From', 'To'],
                    array_map(fn ($c) => [$c['site']->site_code, $c['site']->site_name, $c['from'], $c['to']], $group)
                );
            }
        }

        if (! $dry && ($gained || $lost)) {
            DB::transaction(function () use ($gained, $lost) {
                foreach (array_merge($gained, $lost) as $change) {
                    $change['site']->update(['category' => $change['to']]);
                }
            });
        }

        if ($noVariant) {
            $this->newLine();
            $this->warn('On the list, but the category has no VIP variant, so it stays as it is (' . count($noVariant) . '):');
            $this->table(['Site', 'Name', 'Category', 'Sheet row'], $noVariant);
        }

        if ($invalid) {
            $this->newLine();
            $this->warn('Skipped - the category is not one of SiteCategories (' . count($invalid) . '):');
            $this->table(['Site', 'Name', 'Category'], $invalid);
        }

        if ($notFound) {
            $this->newLine();
            $this->warn('Codes on the list with no site in the sites table (' . count($notFound) . '):');
            $this->table(['Site code', 'Sheet row'], array_map(fn ($code, $row) => [$code, $row], array_keys($notFound), $notFound));

            $file = 'vip-not-found-' . now()->format('Y-m-d_His') . '.csv';
            Storage::disk('local')->put($file, "site_code,sheet_row\n" . implode("\n", array_map(
                fn ($code, $row) => "{$code},{$row}", array_keys($notFound), $notFound
            )) . "\n");
            $this->line('  saved to ' . Storage::disk('local')->path($file));
        }

        $this->newLine();
        $this->info(sprintf(
            '%d on the list, %d found: %d %s VIP, %d %s VIP, %d unchanged, %d without a VIP variant, %d skipped, %d not found.',
            count($list),
            count($list) - count($notFound),
            count($gained),
            $dry ? 'would gain' : 'gained',
            count($lost),
            $dry ? 'would lose' : 'lost',
            $unchanged,
            count($noVariant),
            count($invalid),
            count($notFound),
        ));

        return self::SUCCESS;
    }

    /**
     * The site codes on the first sheet, as code => sheet row (first
     * occurrence). Null, with the reason printed, when the column is missing
     * or holds nothing.
     *
     * @return array<string, int>|null
     */
    private function readList(string $path): ?array
    {
        $sheet = IOFactory::load($path)->getActiveSheet();
        $rows = $sheet->toArray(null, true, false, false);
        $header = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows) ?? []);
        $column = array_search(strtolower(trim($this->option('column'))), $header, true);

        if ($column === false) {
            $this->error("No \"{$this->option('column')}\" column in the first row of {$sheet->getTitle()}. Use --column to name it.");

            return null;
        }

        $list = [];
        $repeated = [];

        foreach ($rows as $i => $row) {
            $code = strtoupper(trim((string) ($row[$column] ?? '')));

            if ($code === '') {
                continue;
            }

            isset($list[$code]) ? $repeated[] = $code : $list[$code] = $i + 2;   // +1 header, +1 one-based
        }

        if (! $list) {
            $this->error("The \"{$this->option('column')}\" column is empty.");

            return null;
        }

        if ($repeated) {
            $this->warn('Listed more than once (counted once): ' . implode(', ', array_unique($repeated)));
        }

        return $list;
    }
}
