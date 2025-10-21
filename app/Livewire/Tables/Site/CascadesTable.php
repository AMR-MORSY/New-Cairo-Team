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

final class CascadesTable extends PowerGridComponent
{
    public string $tableName = 'cascades-table-lvjnmv-table';


    public  $cascades;

    public string $cascadesType;


    public function actions($row): array
    {
        if ($this->cascadesType == 'direct') {
            if (request()->routeIs('site.cascades.update')) {
                return [
                    Button::add('edit-stock')
                        ->icon('default-trash')
                        ->class('cursor-pointer text-red-500')
                        ->confirm('Are you sure you want to delete this PO?')
                        ->tooltip('Delete')
                        ->dispatch('removeCascade', ['site_code' => $row->code]),
                ];
            } else {
                return [
                    Button::add('edit-stock')

                        ->icon('default-eye')
                        ->class('cursor-pointer')
                        ->dispatch('clickToGOSiteDetails', ['site_code' => $row->code]),
                ];
            }
        }
        return [
            Button::add('edit-stock')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToGOSiteDetails', ['site_code' => $row->site_code]),
        ];
    }

    public function datasource(): Collection
    {

        if ($this->cascadesType == 'direct') {
            $count = count($this->cascades);
            $newDataArray = [];
            for ($i = 1; $i <= $count; $i++) {
                $site = [];
                $site['id'] = $i;
                $site['code'] = $this->cascades[$i - 1]['details']['site_code'];
                $site['name'] = $this->cascades[$i - 1]['details']['site_name'];
                $site['count'] =  $this->cascades[$i - 1]['count'];


                array_push($newDataArray, $site);
            }

            return collect($newDataArray);
        }
        return $this->cascades;
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
        if ($this->cascadesType == 'direct') {
            return PowerGrid::fields()
                ->add('id')
                ->add('code')
                ->add('name')
                ->add('count');
        }
        return PowerGrid::fields()
            ->add('id')
            ->add('site_code')
            ->add('site_name');
    }
    // <-- Required for actions


    public function columns(): array
    {
        if ($this->cascadesType == 'indirect') {
            return [


                Column::make('Name', 'site_name')
                    ->searchable()
                    ->sortable(),

                Column::make('Code', 'site_code')
                    ->sortable(),


                Column::action('Action')
            ];
        }
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

    #[On('clickToGOSiteDetails')]
    public function clickToGOSiteDetails(string $site_code)
    {
        return redirect()->route('site.show', ['site' => $site_code]);
    }
}
