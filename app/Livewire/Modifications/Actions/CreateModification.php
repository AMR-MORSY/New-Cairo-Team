<?php

namespace App\Livewire\Modifications\Actions;

use Toaster;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Modification\PO;
use App\Enums\ModificationProjects;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Livewire\Forms\ModificationForm;
use App\Models\Modification\Modification;
use App\Models\Modification\PurchaseOrder;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Modification\ModificationStatus;
use App\Models\Modification\ModificationReservation;

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

        if ($this->form->modification_status_id == 1 or $this->form->modification_status_id == 3) {
            Toaster::error('Modification status is considered done or waiting D6 after quotation submission')->duration('7000');
            return;
        }
        $this->form->inprogressFormSubmission();
    }
    public function render()
    {
        return view('livewire.modifications.actions.create-modification');
    }
}
