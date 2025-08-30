<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Livewire\Forms\ModificationForm;
use App\Models\Modification\Action;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Modification\ModificationStatus;
use App\Models\Site\Site;
use Toaster;

class CreateModification extends Component
{
    public ModificationForm $form;

    public Collection $subcontractors;
    public Collection $requesters;
    public Collection $modificationStatus;
    public Collection $projects;
    public Collection $actions;


    public function mount(
        Project $project,
        Subcontractor $subcontractor,
        Requester $requester,
        ModificationStatus $modification_status,
        Action $action,
        Site $site
    ) {
        $this->subcontractors = $subcontractor->all();
        $this->requesters = $requester->all();
        $this->modificationStatus = $modification_status->all();
        $this->projects = $project->all();
        $this->actions = $action->all();


        $this->form->setModificationDefaultAttributes($site);
    }

    public function create()
    {

        $this->validate();

        // dd( $this->form->all());
        // dd(gettype($this->form->final_cost));
        $modification = Modification::create(
            $this->form->all()
        );

        $modification->actions()->attach($this->form->action_id);
        Toaster::success('Modification created Successfully');

        return redirect()->route('modification.details', $modification->id);
    }
    public function render()
    {
        return view('livewire.modifications.actions.create-modification');
    }
}
