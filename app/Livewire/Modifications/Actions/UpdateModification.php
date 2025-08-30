<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Collection;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Livewire\Forms\ModificationForm;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use App\Models\Modification\ModificationStatus;

class UpdateModification extends Component
{
    public ModificationForm $form;


    public object $modification;

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
        Modification $modification
    ) {
        $this->subcontractors = $subcontractor->all();
        $this->requesters = $requester->all();
        $this->modificationStatus = $modification_status->all();
        $this->projects = $project->all();
        $this->actions = $action->all();

        $this->modification=$modification;

        $modificationActions=$modification->actions->pluck('id');
        $modification->action_id= $modificationActions->toArray();
        
        $this->form->setModificationDetails($modification);
    }

    public function update()
    {
        $this->validate();

        $this->modification->update(
            $this->form->all()
        );

        $this->modification->actions()->sync($this->form->action_id); // Replace all existing relationships with the new array
        Toaster::success('Modification updated Successfully');

        return redirect()->route('modification.details', $this->modification->id);
    }
    public function render()
    {
        return view('livewire.modifications.actions.update-modification');
    }
}
