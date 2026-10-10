<?php

namespace App\Livewire\Modifications\Actions;

use App\Models\Area;
use App\Models\User;
use Livewire\Component;
use App\Models\Site\Site;
use App\Charts\MonthlyUserChart;
use Illuminate\Support\Collection;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Livewire\Forms\ModificationForm;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use App\Enums\ModificationStatus;

class ModificationDetails extends Component
{
    public ModificationForm $form;


    public object $modification;

    public Collection $subcontractors;
    public Collection $requesters;
    /** @var array<int, array{id: string, name: string}> from ModificationStatus::options() */
    public array $modificationStatus = [];
    public Collection $projects;
    public Collection $actions;

    public $site;

    // public $chart;

    public function mount(
        Project $project,
        Subcontractor $subcontractor,
        Requester $requester,
        Action $action,
        Modification $modification,

    ) {
        // $this->chart=$chart->build();
        $this->authorize('viewModificationDetails', $modification);
        $this->subcontractors = $subcontractor->all();
        $this->requesters = $requester->all();
        $this->modificationStatus = ModificationStatus::options();
        $this->projects = $project->all();
        $this->actions = $action->all();
        $this->modification = $modification->load('reservation');
        $this->site = Site::where('site_code', $modification->site_code)->first();


        /////////////////////////////////////////////////////because the form will present the data in input type text as readonly all id attributes should present the name not id number before the modification details goes to form
        $modification->subcontractor_id = $modification->subcontractor->name;
        $modification->requester_id = $modification->requester->name;
        $modification->project_id = $modification->project?->name;
        $modification->action_owner = $modification->actionOwner->name;
        $modification->action_id = $modification->actions->pluck('name')->implode(', ');

        if ($modification->reported == 0) {
            $modification->reported = 'No';
        } else {
            $modification->reported = "Yes";
        }

       
        $this->form->setModificationDetails($modification);

        // readable labels for the read-only form
        $this->form->zone = $modification->zone?->label();
        $this->form->team = $modification->team?->label();

    
    }
    public function render(MonthlyUserChart $chart)
    {
        $chartInstance = $chart->build();
        return view('livewire.modifications.actions.modification-details', ["modification" => $this->modification, 'site' => $this->site, 'chart' =>  $chartInstance]);
    }
}
