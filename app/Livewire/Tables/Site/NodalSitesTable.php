<?php

namespace App\Livewire\Tables\Site;

use App\Enums\Zones;

use App\Models\Site\Site;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Contracts\SiteInterface;
use Illuminate\Support\Collection;
use App\Repositories\SiteRepository;
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

    public function __construct()
    {

        $this->siteRepository = app(SiteRepository::class);
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
    protected function getCachedSitesQuery(): Builder
    {
        $cacheKey = "sites_query_{$this->target}_" . Auth::user()->id;

        return Cache::remember($cacheKey, now()->addHours(10), function () {
                return $this->buildQuery();
            });
    }
    protected function buildQuery(): Builder
    {
        if ($this->target == "nodals") {

            return Site::query()
                ->with(['cascades', 'batteries', 'site_data', 'power_data'])
                ->whereHas('cascades', function ($query) {
                    $query->where('zone', Zones::CAIRO_EAST->value);
                })
                ->selectRaw('sites.*');
        } elseif ($this->target == 'vip') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where(function ($query) {
                $query->where('category', 'VIP')->orWhere('category', 'VIP + NDL')->orWhere('category', 'VIP + NDL + G.SQR');
            })->selectRaw('sites.*');
        } elseif ($this->target == 'TP') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where('site_name', 'like', '%3P%')->selectRaw('sites.*');
        } elseif ($this->target == 'GSquare') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where(function ($query) {
                $query->where('category', 'G.SQR')->orWhere('category', 'VIP + G.SQR')->orWhere('category', 'NDL + G.SQR')->orWhere('category', 'VIP + NDL + G.SQR');
            })->selectRaw('sites.*');
        }
        return Site::query();
    }
    public function datasource(): Builder
    {
        // return $this->getCachedSitesQuery();
         if ($this->target == "nodals") {

            return Site::query()
                ->with(['cascades', 'batteries', 'site_data', 'power_data'])
                ->whereHas('cascades', function ($query) {
                    $query->where('zone', Zones::CAIRO_EAST->value);
                })
                ->selectRaw('sites.*');
        } elseif ($this->target == 'vip') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where(function ($query) {
                $query->where('category', 'VIP')->orWhere('category', 'VIP + NDL')->orWhere('category', 'VIP + NDL + G.SQR');
            })->selectRaw('sites.*');
        } elseif ($this->target == 'TP') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where('site_name', 'like', '%3P%')->selectRaw('sites.*');
        } elseif ($this->target == 'GSquare') {
            return Site::query()->with(['cascades', 'batteries', 'site_data', 'power_data'])->where('zone', Zones::CAIRO_EAST->value)->where(function ($query) {
                $query->where('category', 'G.SQR')->orWhere('category', 'VIP + G.SQR')->orWhere('category', 'NDL + G.SQR')->orWhere('category', 'VIP + NDL + G.SQR');
            })->selectRaw('sites.*');
        }
        return Site::query();
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
            ->add('nodal_code')
            ->add('nodal_name')
            ->add('sharing')
            ->add('host')
            ->add('gest')
            ->add('count_cascades', function ($site) {


                return  count($this->siteRepository->indirectCascades($site->cascades))  +  $site->cascades->count();
            })
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

            Column::make('Nodal Code', 'nodal_code'),

            Column::make('Nodal Name', 'nodal_name'),

            Column::make('Count Cascades', 'count_cascades'),


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
