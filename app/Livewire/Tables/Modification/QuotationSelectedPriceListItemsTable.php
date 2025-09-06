<?php

namespace App\Livewire\Tables\Modification;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class QuotationSelectedPriceListItemsTable extends PowerGridComponent
{
    public string $tableName = 'quotation-selected-price-list-items-table-ve5d4p-table';

    public array $selectedItems = [];

    // public array $modifiedItems = []; // Track modified items


    #[On('selectedItemsUpdated')]
    public function updateSelectedItems($selectedItems)
    {
        $this->selectedItems = $selectedItems;
    }


    public function header(): array
    {

        return [
            Button::add('Insert_Quotation')
                ->slot('<flux:button variant="primary">Insert Items</flux:button>')
                ->class('py-2 px-2 rounded bg-zinc-500 text-white cursor-pointer')
                ->dispatch('insert_Items', []),
        ];
    }

    public function datasource(): Collection
    {
        return  collect($this->selectedItems);
    }

    public function setUp(): array
    {


        return [
            PowerGrid::header(),
            PowerGrid::footer()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('item')
            ->add('description')
            ->add('unit')
            ->add('supply')
            ->add('installation')
            ->add('sup_inst')
            ->add('type')
            ->add('scope')
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
            Column::make('Scope', 'scope'),

            Column::make('Quantity', 'quantity'),

            Column::action('Action')
        ];
    }





    #[On('insert_Items')]

    public function insertQuotation()
    {
        $this->dispatch("insert_Quotation", $this->selectedItems);
    }

    // Add remove action
    public function actions($row): array
    {
        return [
            Button::add('edit-stock')
                ->icon('default-trash')
                ->class('cursor-pointer')
                ->dispatch('removeItem', ['id' => $row->id]),
        ];
    }

    #[On('removeItem')]
    public function removeItem($id)
    {
        // Dispatch to parent to remove the item
        $this->dispatch('removeSelectedItem', id: $id);
    }
}
