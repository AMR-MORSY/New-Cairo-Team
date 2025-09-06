<?php

namespace App\Livewire\Tables\Modification;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class QuotationItemsTable extends PowerGridComponent
{
    public string $tableName = 'quotation-items-table-u7lqrx-table';

    public Collection $quotationItems;

    public string $target = '';

    public function datasource(): Collection
    {
        return $this->quotationItems;
    }

    public function header(): array
    {
        if ($this->target == 'details') {
            return [
                Button::add('update_Q')
                    ->slot('<flux:button variant="primary">Update Quotation</flux:button>')
                    ->class('py-2 px-2 rounded bg-zinc-500 text-white cursor-pointer')
                    ->dispatch('update_quotation', []),
            ];
        }
        if ($this->target == 'update') {
            return [
                Button::add('update_Q')
                    ->slot('<flux:button variant="primary">Add Items</flux:button>')
                    ->class('py-2 px-2 rounded bg-zinc-500 text-white cursor-pointer')
                    ->dispatch('create_quotation', []),
            ];
        }
        return [];
    }

    public function setUp(): array
    {


        return [

            PowerGrid::footer()

                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('Item', 'Description'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('pivot', fn($model) => $model->pivot)
            ->add('item')
            ->add('description')
            ->add('unit')
            ->add('supply', fn($model) => 'EGP' . number_format($model->supply), 2)
            ->add('installation', fn($model) => 'EGP' . number_format($model->installation), 2)
            ->add('sup_inst', fn($model) => 'EGP' . number_format($model->sup_inst), 2)
            ->add('type')
            ->add('scope', fn($model) => $model->pivot['scope'])
            ->add('quantity', fn($model) => $model->pivot['quantity'])
            ->add('item_price', fn($model) => 'EGP' . number_format($model->pivot['item_price']), 2);
    }

    public function columns(): array
    {
        return [


          
            Column::make('Item', 'item')
                ->sortable(),
            Column::make('Description', 'description')
                ->sortable(),
            Column::make('Unit', 'unit')
                ->sortable(),
            Column::make('Supply', 'supply')
                ->sortable(),
            Column::make('Install', 'installation')
                ->sortable(),
            Column::make('Sup_Inst', 'sup_inst')
                ->sortable(),
            Column::make('Type', 'type')
                ->sortable(),
            Column::make('Scope', 'scope')
                ->sortable(),
            Column::make('Quantity', 'quantity')
                ->sortable(),
            Column::make('Item_Price', 'item_price')
                ->sortable(),
            Column::action('Action')



        ];
    }

    public function actions($row): array
    {
        if ($this->target == 'update') {
            return [
             
                Button::add('edit-stock')
                    ->icon('default-trash')
                    ->class('cursor-pointer text-red-500')
                    ->confirm('Are you sure you want to delete the item?')
                    ->tooltip('Delete')
                    ->dispatch('removeItemFromQuotation', ['row' => $row]),
            ];
        }
        return [];
    }

  
}
