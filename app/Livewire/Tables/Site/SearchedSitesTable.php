<?php

namespace App\Livewire\Tables\Site;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SearchedSitesTable extends PowerGridComponent
{
    public string $tableName = 'searched-sites-table-w6g9gq-table';

    public  $props;


    public function actions($row): array
    {
        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToEdit', ['site_code' => $row->site_code]),
        ];
    }
    public function datasource(): Collection
    {
       
        
        return collect($this->props['data']) ;
    }

    public function setUp(): array
    {
       

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code')
            ->add('site_name')
            ->add('zone_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'site_code')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'site_name')
                ->searchable()
                ->sortable(),

            Column::make('OZ', 'zone_name')
                ->sortable(),

            Column::action('View')
        ];
    }

    
    #[On('clickToEdit')]
    public function clickToEdit(string $site_code)
    {
        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
