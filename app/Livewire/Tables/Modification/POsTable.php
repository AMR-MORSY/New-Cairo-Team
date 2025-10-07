<?php

namespace App\Livewire\Tables\Modification;

use App\Policies\PurchaseOrderPolicy;
use Auth;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class POsTable extends PowerGridComponent
{
    public string $tableName = 'p-os-table-trfq70-table';

    public Collection|null $POs = null;


    #[On('showPos')]

    public function showPos($POs)
    {

        $this->POs = collect($POs);
    }

    public function datasource(): Collection
    {
        if ($this->POs) {
            return $this->POs;
        } else {
            return collect([]);
        }
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
        return PowerGrid::fields()
            ->add('id')
            ->add('subcontractor_id', fn($model) => $model->subcontractor['name'])
            ->add('type')
            ->add('amount', fn($model) => 'EGP ' . number_format($model->amount), 2)
            ->add('invoiced', fn($model) => 'EGP ' . number_format($model->invoiced), 2)
            ->add('in_progress', fn($model) => 'EGP ' . number_format($model->in_progress), 2)
            ->add('on_hand', fn($model) => 'EGP ' . number_format($model->on_hand), 2)
            ->add('status')

            ->add('issued_at', function ($entry) {

                return Carbon::parse($entry->created_at)->format('d/m/Y');
            });
    }

    public function columns(): array
    {
        return [


            Column::make('Subcontractor', 'subcontractor_id')

                ->sortable(),

            Column::make('Type', 'type')
                ->sortable(),
            Column::make('Amount', 'amount')
                ->sortable(),
            Column::make('Invoiced', 'invoiced')
                ->sortable(),
            Column::make('In progress', 'in_progress')
                ->sortable(),
            Column::make('On Hand', 'on_hand')
                ->sortable(),

            Column::make('Issuing Date', 'issued_at'),

            Column::action('Action')
        ];
    }


    public function actions($row): array
    {


        return [
            Button::add('po-modifications')
                ->slot('In progress modifications')
                ->class('cursor-pointer text-blue-500')
                ->route('po.inprogress.modifications', ['purchaseOrder' => $row->id]),
            Button::add('po-modifications')
                ->slot('Invoiced modifications')
                ->class('cursor-pointer text-green-500')
                ->route('po.invoiced.modifications', ['purchaseOrder' => $row->id]),
            // ->can(Auth::user()->isModificationAdmin()),

            Button::add('edit-stock')
                ->icon('default-trash')
                ->class('cursor-pointer text-red-500')
                ->confirm('Are you sure you want to delete this PO?')
                ->tooltip('Delete')
                ->dispatch('removePO', ['id' => $row->id]),
        ];
    }
}
