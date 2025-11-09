<?php

namespace App\Livewire\Tables\Site;

use App\Models\Site\Site;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use App\Contracts\SiteInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class NodalSitesTable extends PowerGridComponent
{
    // use WithExport;
    // public string $tableName = 'nodal-sites-table-5ca6kn-table';

    // public $nodalSites;

    // public $perPage='nodalSites';


    // public function datasource(): Collection
    // {
    //     if ($this->nodalSites) {

    //         return $this->nodalSites;
    //     }
    //     return collect();

    // }

    // #[On('nodal_sites')]
    // public function nodal_sites($sites,$perPage)
    // {

    //     $this->nodalSites = collect($sites);
    //     $this->perPage=$perPage;


    // }


    // public function setUp(): array
    // {


    //     return [
    //         PowerGrid::header()
    //             ->showSearchInput(),
    //         PowerGrid::footer()
    //             ->pageName("nodalPage")
    //             ->showRecordCount(),
    //         PowerGrid::responsive()->fixedColumns('site_code', 'site_name'),
    //         PowerGrid::exportable(fileName: 'nodal sites')
    //             ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
    //     ];
    // }

    // public function fields(): PowerGridFields
    // {
    //     return PowerGrid::fields()
    //         ->add('id')
    //         ->add('site_code')
    //         ->add('site_name')
    //         ->add('category')
    //         ->add('nodal_code')
    //         ->add('nodal_name')
    //         ->add('count_cascades')
    //         ->add('battery_brand')
    //         ->add('no_strings')
    //         ->add('installation_date');
    // }

    // public function columns(): array
    // {
    //     return [
    //         Column::make('site_code', 'site_code')
    //             ->searchable()
    //             ->sortable(),

    //         Column::make('site_name', 'site_name')
    //             ->searchable()
    //             ->sortable(),

    //              Column::make('category', 'category')
    //             ->searchable()
    //             ->sortable(),

    //         Column::make('count_cascades', 'count_cascades')
    //             ->sortable(),

    //         Column::make('battery_brand', 'battery_brand'),

    //         Column::make('no_strings', 'no_strings'),
    //         Column::make('installation_date', 'installation_date'),
    //              Column::make('nodal_code', 'nodal_code')
    //             ->searchable()
    //             ->sortable(),

    //         Column::make('nodal_name', 'nodal_name')
    //             ->searchable()
    //             ->sortable(),
    //     ];
    // }




    use WithExport;

    public string $tableName = 'nodal-sites-table-5ca6kn-table';

    public $target;

    protected $siteRepository;

    public function __construct()
    {
        // parent::__construct();
        $this->siteRepository = app(SiteInterface::class);
    }

    public function datasource(): Builder
    {
        if ($this->target == "nodals") {
            return Site::query()
                ->with(['cascades', 'batteries'])
                ->whereHas('cascades', function ($query) {
                    $query->where('zone_id', 2);
                })
                ->selectRaw('sites.*');
        } elseif ($this->target == 'vip') {
            return Site::query()->with(['cascades', 'batteries'])->where('zone_id', 2)->where(function ($query) {
                $query->where('category', 'VIP')->orWhere('category', 'VIP + NDL')->orWhere('category', 'VIP + NDL + G.SQR');
            })->selectRaw('sites.*');
        } elseif ($this->target == 'TP') {
            return Site::query()->with(['cascades', 'batteries'])->where('zone_id', 2)->where('site_name', 'like', '%3P%')->selectRaw('sites.*');
        } elseif ($this->target == 'GSquare') {
            return Site::query()->with(['cascades', 'batteries'])->where('zone_id', 2)->where(function ($query) {
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
            ->add('count_cascades', function ($site) {
                // Calculate cascades count
                // $directCount = $site->cascades->count();
                // $indirectCount = 0;

                // If you have the SiteInterface dependency, inject it
                // For now, you might need to add this logic directly
                // $indirectCount = count($this->getIndirectCascades($site->cascades));

                return  count($this->siteRepository->indirectCascades($site->cascades))  +  $site->cascades->count();

                // return $directCount + $indirectCount;
            })
            ->add('battery_brand', function ($site) {
                return $site->batteries ? $site->batteries->pluck('batteries_brand')->implode("\n") : '';
            })
            ->add('no_strings', function ($site) {
                return $site->batteries ? $site->batteries->pluck('no_strings')->implode("\n") : '';
            })
            ->add('installation_date', function ($site) {
                return $site->batteries ? $site->batteries->pluck('installation_date')->implode("\n") : '';
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

            Column::make('Nodal Code', 'nodal_code')
                ->searchable()
                ->sortable(),

            Column::make('Nodal Name', 'nodal_name')
                ->searchable()
                ->sortable(),

            Column::make('Count Cascades', 'count_cascades'),
                

            Column::make('Battery Brand', 'battery_brand'),

            Column::make('No Strings', 'no_strings'),

            Column::make('Installation Date', 'installation_date'),
        ];
    }
}
