<?php

namespace App\Livewire\Tables\Site;

use App\Enums\SiteCategories;
use App\Enums\Zones;
use App\Support\PowerGrid\Themes\CompactDaisyUI;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Contracts\SiteInterface;
use Illuminate\Support\Collection;
use App\Services\Sites\SiteRouteMap;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class NodalSitesTable extends PowerGridComponent
{

    use WithExport;

    public string $tableName = 'nodal-sites-table-5ca6kn-table';

    public $target;

    protected $siteRepository;

    /** Built once per request; it loads site_routes a single time for every row. */
    protected ?SiteRouteMap $routeMap = null;

    
  /** A dozen columns wide; the compact theme keeps them on one screen. */
    public function customThemeClass(): ?string
    {
        return CompactDaisyUI::class;
    }


   

    public function actions($row): array
    {


        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToGOSiteDetails', ['site_code' => $row->site_code]),
        ];
    }

    /**
     * Nodality, cascades and parents all come from site_routes (the planning
     * sheets), with the same rules as the site page and update:sites-nodality:
     * a link drawn red is not live.
     */
    protected function routeMap(): SiteRouteMap
    {
        return $this->routeMap ??= new SiteRouteMap();
    }

    /**
     * sites.* plus count_cascades as a real column, so PowerGrid can sort on
     * it in SQL. The counts come from site_routes, which SQL cannot see, so
     * they are written into the query as
     * CASE site_code WHEN ? THEN ? ... ELSE 0 END - one arm per site with
     * anything cascaded on it, a few hundred.
     *
     * @return array{0: string, 1: array}
     */
    protected function selectWithCascadeCount(): array
    {
        $counts = $this->routeMap()->cascadeCounts();

        if (! $counts) {
            return ['sites.*, 0 as count_cascades', []];
        }

        $bindings = [];
        foreach ($counts as $code => $count) {
            array_push($bindings, $code, $count);
        }

        $arms = str_repeat(' WHEN ? THEN ?', count($counts));

        return ["sites.*, CASE sites.site_code{$arms} ELSE 0 END as count_cascades", $bindings];
    }

    protected function getCachedSitesQuery(): Builder
    {
        $cacheKey = "sites_query_{$this->target}_" . Auth::user()->id;

        return Cache::remember($cacheKey, now()->addHours(10), function () {
                return $this->buildQuery();
            });
    }
    /**
     * Which Cairo East sites each page lists. Public and static so the Routes
     * tab (SiteRoutesWithBatteries) lists exactly the sites this table does.
     */
    public static function sites(string $target, ?SiteRouteMap $map = null): Builder
    {
        $query = Site::query()->where('zone', Zones::CAIRO_EAST->value);

        return match ($target) {
            // the nodals as the planning sheets draw them, not the old cascades table
            'nodals' => $query->whereIn('site_code', ($map ?? new SiteRouteMap())->nodalCodes()),
            'vip' => $query->whereIn('category', SiteCategories::withTag(SiteCategories::VIP)),
            'TP' => $query->where('site_name', 'like', '%3P%'),
            'GSquare' => $query->whereIn('category', SiteCategories::withTag(SiteCategories::GOLDEN_SQUARE)),
            default => Site::query(),
        };
    }

    protected function buildQuery(): Builder
    {
        if (! in_array($this->target, ['nodals', 'vip', 'TP', 'GSquare'], true)) {
            return Site::query();
        }

        return self::sites($this->target, $this->routeMap())
            ->with($this->target === 'nodals' ? ['batteries', 'site_data', 'power_data'] : ['cascades', 'batteries', 'site_data', 'power_data'])
            ->selectRaw(...$this->selectWithCascadeCount());
    }
    public function datasource(): Builder
    {
        // return $this->getCachedSitesQuery();
        return $this->buildQuery();
    }

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::responsive()->fixedColumns('site_code', 'site_name'),

        ];
        if ($this->target == 'nodals') {
            $setup[] = PowerGrid::footer()
                ->pageName("nodalPage")
                ->showRecordCount()
                ->showPerPage();
            $setup[]  =   PowerGrid::exportable(fileName: 'nodal sites')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
        } elseif ($this->target == 'vip') {
            $setup[] = PowerGrid::footer()
                ->pageName("vipPage")
                ->showRecordCount()
                ->showPerPage();
            $setup[]  =   PowerGrid::exportable(fileName: 'VIP sites')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
        } elseif ($this->target == 'TP') {
            $setup[] = PowerGrid::footer()
                ->pageName("TPPage")
                ->showRecordCount()
                ->showPerPage();
            $setup[]  =   PowerGrid::exportable(fileName: 'TP sites')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
        } elseif ($this->target == 'GSquare') {
            $setup[] = PowerGrid::footer()
                ->pageName("GSquarePage")
                ->showRecordCount()
                ->showPerPage();
            $setup[]  =   PowerGrid::exportable(fileName: 'GSquare sites')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
        }

        return $setup;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code')
            ->add('site_name')
            ->add('category')
            // // the site(s) this one hangs off in the sheets; several when there are alternate routes
            // ->add('nodal_code', function ($site) {
            //     return implode("\n", array_keys($this->routeMap()->parentsOf($site->site_code)));
            // })
            // ->add('nodal_name', function ($site) {
            //     // one line per parent, so it lines up with nodal_code even when a name is missing
            //     return implode("\n", array_map(fn ($name) => $name ?: '—', $this->routeMap()->parentsOf($site->site_code)));
            // })
            ->add('sharing')
            ->add('host')
            ->add('gest')
            // the same count the site page shows, computed in the query so the column can sort
            ->add('count_cascades', fn ($site) => (int) $site->count_cascades)
            ->add('battery_brand', function ($site) {
                return $site->batteries ? $site->batteries->pluck('batteries_brand')->implode("\n") : '';
            })
            ->add('no_strings', function ($site) {
                return $site->batteries ? $site->batteries->pluck('no_strings')->implode("\n") : '';
            })
            ->add('installation_date', function ($site) {
                return $site->batteries ? $site->batteries->pluck('installation_date')->implode("\n") : '';
            })
            ->add('power_source', function ($site) {
                return $site->power_data ? $site->power_data->power_source : '';
            })
            ->add('topology', function ($site) {
                return $site->site_data ? $site->site_data->topology : '';
            })
            ->add('structure', function ($site) {
                return $site->site_data ? $site->site_data->structure : '';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Site Code', 'site_code')
                ->searchable()
                ->sortable(),

            Column::make('Site Name', 'site_name')
                ->searchable()
                ->sortable(),

            Column::make('Category', 'category')

                ->sortable(),
            Column::make('Sharing', 'sharing')

                ->sortable(),
            Column::make('Host', 'host')

                ->sortable(),
            Column::make('Guest', 'gest')

                ->sortable(),

            // Column::make('Nodal Code', 'nodal_code'),

            // Column::make('Nodal Name', 'nodal_name'),

            Column::make('Count Cascades', 'count_cascades')
              ->sortable(),


            Column::make('Battery Brand', 'battery_brand'),

            Column::make('No Strings', 'no_strings'),

            Column::make('Installation Date', 'installation_date'),
            Column::make('Power Source', 'power_source'),
            Column::make('Topology', 'topology'),
            Column::make('Structure', 'structure'),
            Column::action('Action'),
        ];
    }

    #[On('clickToGOSiteDetails')]
    public function clickToGOSiteDetails($site_code)
    {


        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
