<?php

namespace App\Livewire\Tables\Modification;

use App\Rules\UnpricedItemsCheck;
use Toaster;

use Livewire\Attributes\On;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\Facades\Validator;

use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SearchedPriceListItemTable extends PowerGridComponent///////table contains search result items. It is shown in the modal
{
    public string $tableName = 'searched-price-list-item-table-sj5yal-table';

    public  $props;

    public $selectedScope = 'install';
    public $items;

    public function actions($row): array
    {
        return [
            Button::add('edit-stock')

                ->icon('default-archive-box-arrow-down')
                ->class('cursor-pointer')
                ->dispatch('clickToAddItemToQuotation', ['row' => $row])
                ->tooltip('Add item')
        ];
    }
    public function datasource(): Collection
    {
        return collect($this->props['data']);
    }

    public function setUp(): array
    {


        return [

            PowerGrid::footer()

                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        $scopes = ['supply', 'install', 'S&I'];
        return PowerGrid::fields()
            ->add('id')
            ->add('item')
            ->add('description')
            ->add('unit')
            ->add('supply')
            ->add('installation')
            ->add('sup_inst')
            ->add('type')
            ->add('scope', function () use ($scopes) {

                return Blade::render(
                    '<x-select-scope type="occurrence"  :scopes=$scopes :selected="$selected"  />',
                    [

                        'scopes' =>  $scopes,
                        'selected' => 'install',



                    ]
                );
            })
            ->add('quantity');
    }

    public function columns(): array
    {
        return [
            Column::make('Item', 'item')
                ->searchable()
                ->sortable(),

            Column::make('Description', 'description')
                ->searchable()
                ->sortable(),

            Column::make('Unit', 'unit')
                ->sortable(),

            Column::make('Supply', 'supply')
                ->sortable(),
            Column::make('Installation', 'installation')
                ->sortable(),
            Column::make('Sup_Inst', 'sup_inst')
                ->sortable(),
            Column::make('Type', 'type')
                ->sortable(),
            Column::make('Scope', 'scope'), // Enable inline editing

            Column::make('Quantity', 'quantity')
                ->editOnClick(true, 'quantity'),

            Column::action('Action')
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void /// to add the quantity
    {

        foreach ($this->props['data'] as $index => $item) {
            if ($item['id'] == $id) {
                $this->props['data'][$index][$field] = $value;


                break;
            }
        }
    }

    #[On('scopeChanged')]
    public function scopeChanged($scopeValue): void ////////controlling scope dropdown menu select input
    {

        $this->selectedScope = $scopeValue;
        $this->props['data'][0]['scope'] = $scopeValue;
    }

    #[On('clickToAddItemToQuotation')]
    public function addItemToQuotationTable($row)
    {
        $row['scope'] = $this->selectedScope;
        // dd($row);
        $validator = Validator::make($row, [
            'scope' => 'required',
            'quantity' => [new UnpricedItemsCheck($row), 'regex:/^(?!0\d)(?:\d+|\d*\.\d+)$/', 'max:1000000']
        ]);
        //  dd($validator);
        if ($validator->fails()) {
            // dd($validator);
            $errors = $validator->errors();
            if ($errors->first('scope')) {
                Toaster::error($errors->first('scope'));
            }
            if ($errors->first('quantity')) {
                Toaster::error($errors->first('quantity'));
            }
        } else {



            $this->dispatch('selectedItemEdited',  $row);
        }
    }
}
