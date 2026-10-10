<?php

namespace App\Livewire\Tables\Modification;

use App\Models\Site\Site;
use App\Support\PowerGrid\Themes\CompactDaisyUI;
use Livewire\Attributes\On;
use Illuminate\Support\Carbon;

use Illuminate\Support\Number;
use Illuminate\Support\Collection;
use App\Models\Modification\Modification;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
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


    /** A dozen columns wide; the compact theme keeps them on one screen. */
    public function customThemeClass(): ?string
    {
        return CompactDaisyUI::class;
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
                // status is an enum on the model now; the modification_status
                // relation went with the table it pointed at
                $modification->status_name = $modification->status?->value ?? '';
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
            /*
             * Returning '' here was the bug behind
             * '"" is not a valid backing value for ModificationStatus':
             * PowerGrid writes each field back onto the row, so an empty
             * string landed on a column the model casts to the enum, and the
             * next read of it threw. The value has to stay a real case.
             */
            /*
             * Named apart from the `status` column on purpose. PowerGrid puts
             * each field back on the row, and a field sharing a name with an
             * enum-cast attribute comes back out as the enum object, which the
             * row view then hands to preg_replace(). Modification::$status is
             * the only enum cast among the tables, so only this one collides.
             */
            ->add('status_label', fn ($model) => $model->status?->value ?? '')
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
            // Column::add()
            //     ->title('Code')
            //     ->field('site_code')
            //     ->searchable(),
            // Column::add()
            //     ->title('Site')
            //     ->field('site_name')
            //     ->searchable(),
            Column::add()
                ->title('Subcontractor')
                ->field('subcontractor')
                ->searchable()
                ->sortable(),
            Column::make('WO', 'wo_code')
                ->searchable()
                ->sortable(),

            Column::make('Status', 'status_label')
                ->sortable()
                // keyed by the ModificationStatus backing values
                ->contentClasses([
                    'Done' => 'badge badge-sm badge-success',
                    'In Progress' => 'badge badge-sm badge-info',
                    'Waiting D6' => 'badge badge-sm badge-warning',
                    'Cancelled' => 'badge badge-sm badge-error',
                ]),
            Column::make('Requester', 'requester')
                ->searchable()
                ->sortable(),
            Column::make('Owner', 'actionOwner'),
            Column::make('Project', 'project'),

            Column::make('Request_date', 'request_date'),
            Column::make('Est. Cost', 'est_cost_formatted')
                ->headerAttribute('text-right')
                ->bodyAttribute('text-right font-mono tabular-nums whitespace-nowrap'),
            Column::make('Final Cost', 'final_cost_formatted')
                ->headerAttribute('text-right')
                ->bodyAttribute('text-right font-mono tabular-nums whitespace-nowrap'),
            Column::make('Actions', 'actions'),
            Column::action('View')
        ];
    }


    /** Cancelled work stays in the list but should not read as live. */
    // public function actionRules($row): array
    // {
    //     return [
    //         Rule::rows()
    //             ->when(fn ($row) => $row->status_label === 'Cancelled')
    //             ->setAttribute('class', 'opacity-60'),
    //     ];
    // }

    #[On('clickToGoModificationDetails')]
    public function clickToGoModificationDetails($modificationId)
    {
        return redirect()->route('modification.details', ['modification' => $modificationId]);
    }
}
