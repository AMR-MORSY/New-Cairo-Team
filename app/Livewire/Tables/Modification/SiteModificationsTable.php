<?php

namespace App\Livewire\Tables\Modification;

use Livewire\Attributes\On;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

use Illuminate\Support\Collection;
use App\Models\Modification\Modification;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SiteModificationsTable extends PowerGridComponent
{

    public string $tableName = 'site-modifications-table-nrpqqf-table';

    public Collection $modifications;



    public function actions($row): array
    {
        return [
            Button::add('go-to-modification-details')

                ->icon('default-eye')
                ->class('cursor-pointer')
                ->dispatch('clickToGoModificationDetails', ['modificationId' => $row->id]),
        ];
    }

    public function datasource(): Collection
    {



        ///////because we cast the est_cost & final_cost number_format will not work on these attributes, so we made new formatted attributes for both est & final costs to be used as fields

        $modifications = $this->modifications->map(function ($modification) {
            // Add formatted properties to the model object
            $modification->est_cost_formatted = number_format($modification->est_cost, 2);
            $modification->final_cost_formatted = number_format($modification->final_cost, 2);
            return $modification;
        });

        return $modifications;
    }

    public function setUp(): array
    {


        return [

            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('WO', 'Subcontractor'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('subcontractor', fn($model) => $model->subcontractor?->name)
            ->add('wo_code')
            ->add('requester', fn($model) => $model->requester?->name)
            ->add('status', fn($model) => $model->modification_status?->name)
            ->add('actionOwner', fn($model) => $model->actionOwner?->name)
            ->add('project', fn($model) => $model->project?->name)
            ->add('request_date')
            ->add('actions', function ($model) {
                return $model->actions->pluck('name')->implode(', ');
            })
            ->add('est_cost_formatted')
            ->add('final_cost_formatted');
    }

    public function columns(): array
    {
        return [
            Column::make('Subcontractor', 'subcontractor')
                ->searchable()
                ->sortable(),
                 Column::make('WO', 'wo_code')
                ->searchable()
                ->sortable(),

            Column::make('Status', 'status')
                ->sortable(),
            Column::make('Requester', 'requester')
                ->searchable()
                ->sortable(),
            Column::make('Owner', 'actionOwner'),
            Column::make('Project', 'project'),
            // Column::add()
            //     ->title('Project')
            //     ->field('project')
            //     ->editOnClick(),
            Column::make('Request_date', 'request_date'),
            Column::make('Est. Cost', 'est_cost_formatted'),
            Column::make('Final Cost', 'final_cost_formatted'),
            Column::make('Actions', 'actions'),
            Column::action('View')
        ];
    }


    #[On('clickToGoModificationDetails')]
    public function clickToGoModificationDetails($modificationId)
    {
        return redirect()->route('modification.details', ['modification' => $modificationId]);
    }
}
