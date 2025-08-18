<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SiteIndirectCascadesTable extends PowerGridComponent
{
    public string $tableName = 'site-indirect-cascades-table-mlwlz5-table';

    public Collection  $data;


    public function actions($row): array
    {
        return [
            Button::add('edit-stock')

                ->icon('default-bolt')
                ->class('cursor-pointer')
                ->dispatch('clickToEdit', ['site_code' => $row->code]),
        ];
    }


    public function datasource(): Collection
    {


        return $this->data;
    }

    public function setUp(): array
    {
        $this->showCheckBox();

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
            ->add('site_code')
            ->add('site_name');
        // ->add('created_at_formatted', function ($entry) {
        //     return Carbon::parse($entry->created_at)->format('d/m/Y');
        // });
    }

    public function columns(): array
    {
        return [


            Column::make('Name', 'site_name')
                ->searchable()
                ->sortable(),

            Column::make('Code', 'site_code')
                ->sortable(),

            // Column::make('Created', 'created_at_formatted'),

            Column::action('Action')
        ];
    }

    #[On('clickToEdit')]
    public function clickToEdit(string $site_code)
    {
        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
