<?php

namespace App\Livewire\Tables\Site;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MuxPlansTable extends PowerGridComponent
{
    public string $tableName = 'mux-plans-table-5koask-table';

    public $muxPlans;

    public function datasource(): Collection
    {
        if ($this->muxPlans) {
            return $this->muxPlans;
        }
        return collect();
    }

    #[On('mux_plans')]
    public function nodal_sites($mux_plans)
    {
        // dd($nodalSites);
        $this->muxPlans = collect($mux_plans);
    }
    public function setUp(): array
    {


        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ne')
            ->add('ne_ip')
            ->add('ne_slot')
            ->add('fe')
            ->add('fe_slot');
    }

    public function columns(): array
    {
        return [
            Column::make('NE', 'ne')

                ->sortable(),

            Column::make('NE.IP', 'ne_ip')

                ->sortable(),

            Column::make('NE.Slot', 'ne_slot')
                ->sortable(),

            Column::make('FE', 'fe')
                ->sortable(),
            Column::make('FE.Slot', 'fe_slot')
                ->sortable(),


        ];
    }
}
