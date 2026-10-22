<?php

namespace App\Console\Commands;

use App\Enums\SiteCategories;
use App\Models\Site\Site;
use App\Models\Site\SiteRoute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * A site with anything cascaded on it is a nodal, so its category gains NDL
 * ("TP" -> "TP + NDL") via SiteCategories::mapSiteNodality(). The parents are
 * read from site_routes, which ImportSiteRoutes fills from the planning sheets.
 *
 * Only adds NDL: a site that stopped being a parent keeps its category.
 */
class UpdateSitesNodality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:sites-nodality {--dry-run : Report what would change without writing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add NDL to the category of every site that has sites cascaded on it in site_routes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dry = (bool) $this->option('dry-run');

        if ($dry) {
            $this->warn('dry run - nothing will be written');
        }

        // distinct rather than groupBy: ONLY_FULL_GROUP_BY rejects grouping with raw_label selected
        $parentCodes = SiteRoute::query()->distinct()->pluck('parent_code')
            ->map(fn($code) => strtoupper(trim($code)))
            ->unique()
            ->values();

        $sites = $parentCodes->chunk(1000)
            ->flatMap(fn($codes) => Site::whereIn('site_code', $codes->all())->get(['id', 'site_code', 'site_name', 'category']))
            ->keyBy(fn($site) => strtoupper($site->site_code));

        $notFound = $parentCodes->reject(fn($code) => $sites->has($code))->values();

        $changes = [];
        $unchanged = 0;
        $invalid = [];

        foreach ($sites as $site) {
            $category = SiteCategories::mapSiteNodality($site->category);

            if ($category === null) {
                $invalid[] = [$site->site_code, $site->site_name, $site->category];
                continue;
            }

            if ($category === $site->category) {
                $unchanged++;
                continue;
            }

            $changes[] = ['site' => $site, 'from' => $site->category, 'to' => $category];
        }

        if ($changes) {
            $this->table(
                ['Site', 'Name', 'From', 'To'],
                array_map(fn($c) => [$c['site']->site_code, $c['site']->site_name, $c['from'], $c['to']], $changes)
            );
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

        if ($notFound->isNotEmpty()) {
            $this->warn("Parents in site_routes with no site in the sites table ({$notFound->count()}):");
            $this->line($notFound->implode(', '));
        }

        $this->newLine();
        $this->info(sprintf(
            '%d parent sites: %d %s, %d already nodal or without an NDL variant, %d skipped, %d not found.',
            $parentCodes->count(),
            count($changes),
            $dry ? 'would change' : 'changed',
            $unchanged,
            count($invalid),
            $notFound->count(),
        ));

        return self::SUCCESS;
    }
}
