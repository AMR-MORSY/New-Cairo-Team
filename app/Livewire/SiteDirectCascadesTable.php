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
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Responsive;

final class SiteDirectCascadesTable extends PowerGridComponent
{
    public string $tableName = 'site-details-table-hrh4tz-table';

    public array $data;


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

        $count = count($this->data);
        $newDataArray = [];
        for ($i = 1; $i <= $count; $i++) {
            $site = [];
            $site['id'] = $i;
            $site['code'] = $this->data[$i - 1]['details']['site_code'];
            $site['name'] =  $this->data[$i - 1]['details']['site_name'];
            $site['count'] =  $this->data[$i - 1]['count'];
            array_push($newDataArray, $site);
        }




        return collect($newDataArray);
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
            ->add('code')
            ->add('name')
            ->add('count');
        // ->add('created_at_formatted', function ($entry) {
        //     return Carbon::parse($entry->created_at)->format('d/m/Y');
        // });
    }
    // <-- Required for actions


    public function columns(): array
    {
        return [

            Column::make('Code', 'code')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Count Cascades', 'count')
                ->sortable(),
            Column::action('Action'),




        ];
    }

    #[On('clickToEdit')]
    public function clickToEdit(string $site_code)
    {
        return redirect()->route('site.show',['site'=>$site_code]);
    }
}
