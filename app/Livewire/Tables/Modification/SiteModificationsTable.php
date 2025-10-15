<?php

namespace App\Livewire\Tables\Modification;

use App\Models\Site\Site;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

use Illuminate\Support\Number;
use Illuminate\Support\Collection;
use App\Models\Modification\Modification;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;

final class SiteModificationsTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'site-modifications-table-nrpqqf-table';

    public Collection|null $modifications;

    public  $site;


    #[On('modificationsFound')]
    public function modificationsFound($modifications)
    {

        $this->modifications = Modification::hydrate($modifications); /////when dispatching a collection of models, it is received as an array of arrays. This method return into original value.a collection of models
        // dd($this->modifications);
    }


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






        if ($this->modifications) {
            ///////because we cast the est_cost & final_cost number_format will not work on these attributes, so we made new formatted attributes for both est & final costs to be used as fields

            $modifications = $this->modifications->map(function ($modification) {
                // Add formatted properties to the model object

                $modification->est_cost_formatted = number_format($modification->est_cost, 2);
                $modification->final_cost_formatted = number_format($modification->final_cost, 2);

                $modification->site_name = $modification->site->site_name ?? '';

                // Add export-friendly relationship data
                $modification->subcontractor_name = $modification->subcontractor?->name ?? '';
                $modification->requester_name = $modification->requester?->name ?? '';
                $modification->status_name = $modification->modification_status?->name ?? '';
                $modification->action_owner_name = $modification->actionOwner?->name ?? '';
                $modification->project_name = $modification->project?->name ?? '';
                $modification->actions_list = $modification->actions ? $modification->actions->pluck('name')->implode(', ') : '';

                return $modification;
            });
            return $modifications;
        }


        return collect();
    }

    public function setUp(): array
    {
        $setup = [

            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('WO', 'Subcontractor'),

        ];
        if ($this->site) {
            $setup[] =


                PowerGrid::exportable(fileName: $this->site->site_code . '-' . $this->site->site_name)
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
           
        } elseif (!$this->site) {
            $setup[] =


                PowerGrid::exportable(fileName: 'Modifications')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV);
         
        }
        return $setup;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()

            ->add('id')
            ->add('site_code')
            ->add('site_name')
            ->add('subcontractor', function ($model) {
                // Handle both object and string cases as the excel download library returns the whole object relation  
                if (is_object($model->subcontractor)) {
                    return $model->subcontractor->name;
                }
                return $model->subcontractor_name ?? '';
            })
            ->add('wo_code')
            ->add('requester', function ($model) {
                if (is_object($model->requester)) {
                    return $model->requester->name;
                }
                return $model->requester_name ?? '';
            })
            ->add('status', function ($model) {
                if (is_object($model->modification_status)) {
                    return $model->modification_status->name;
                }
                return $model->status_name ?? '';
            })
            ->add('actionOwner', function ($model) {
                if (is_object($model->actionOwner)) {
                    return $model->actionOwner->name;
                }
                return $model->action_owner_name ?? '';
            })
            ->add('project', function ($model) {
                if (is_object($model->project)) {
                    return $model->project->name;
                }
                return $model->project_name ?? '';
            })
            ->add('request_date')
            ->add('actions', function ($model) {
                if (is_object($model->actions)) {
                    return $model->actions->pluck('name')->implode(', ');
                }
                return $model->actions_list ?? '';
            })
            ->add('est_cost_formatted')
            ->add('final_cost_formatted');
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('Code')
                ->field('site_code')
                ->searchable(),
            Column::add()
                ->title('Site')
                ->field('site_name')
                ->searchable(),
            Column::add()
                ->title('Subcontractor')
                ->field('subcontractor')
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
