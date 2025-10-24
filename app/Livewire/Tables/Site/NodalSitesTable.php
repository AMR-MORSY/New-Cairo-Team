<?php

namespace App\Livewire\Tables\Site;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class NodalSitesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'nodal-sites-table-5ca6kn-table';

    public $nodalSites;


    public function datasource(): Collection
    {
        if ($this->nodalSites) {
            // $this->nodalSites=$this->nodalSites->map(function($nodal){
            //     $nodal->cascades_count=intval($nodal->cascades_count);
            //     return $nodal;
            // });
            return $this->nodalSites;
        }
        return collect();
    }

    #[On('nodal_sites')]
    public function nodal_sites($sites)
    {
        // dd($nodalSites);
        $this->nodalSites = collect($sites);
    }


    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive()->fixedColumns('site_code', 'site_name'),
            PowerGrid::exportable(fileName: 'nodal sites')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
        ];
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
            ->add('count_cascades')
            ->add('battery_brand')
            ->add('no_strings')
            ->add('installation_date');
    }

    public function columns(): array
    {
        return [
            Column::make('site_code', 'site_code')
                ->searchable()
                ->sortable(),

            Column::make('site_name', 'site_name')
                ->searchable()
                ->sortable(),

                 Column::make('category', 'category')
                ->searchable()
                ->sortable(),
       



            Column::make('count_cascades', 'count_cascades')
                ->sortable(),

            Column::make('battery_brand', 'battery_brand'),

            Column::make('no_strings', 'no_strings'),
            Column::make('installation_date', 'installation_date'),
                 Column::make('nodal_code', 'nodal_code')
                ->searchable()
                ->sortable(),

            Column::make('nodal_name', 'nodal_name')
                ->searchable()
                ->sortable(),
        ];
    }
}
