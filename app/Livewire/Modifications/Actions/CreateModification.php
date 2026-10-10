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
use App\Enums\ModificationStatus;
use App\Models\Modification\ModificationReservation;

class CreateModification extends Component
{
    public ModificationForm $form;

    public Collection $subcontractors;
    public Collection $requesters;
    /** @var array<int, array{id: string, name: string}> from ModificationStatus::options() */
    public array $modificationStatus = [];
    public Collection $projects;
    public Collection $actions;

    public Site $site;

    public function mount(
        Project $project,
        Subcontractor $subcontractor,
        Requester $requester,
        Action $action,
        Site $site
    ) {
        $this->subcontractors = $subcontractor->active()->get();
        $this->requesters = $requester->active()->get();
        $this->modificationStatus = ModificationStatus::options();
        $this->projects = $project->active()->get();
        $this->actions = $action->active()->get();
        $this->authorize('create', [Modification::class, $site]);
        $this->site = $site;


        $this->form->setModificationDefaultAttributes($site);
    }





    public function create()
    {

        $this->validate();

        // if (ModificationStatus::tryFrom($this->form->status)?->isQuoted()) { /////////////////status done or waiting D6
        //     Toaster::error('Modification status is considered done or waiting D6 after quotation submission')->duration('7000');
        //     return;
        // }   /////// i will pass the checking of the quotation existence this time 
        $this->form->inprogressFormSubmission();
    }
    public function render()
    {
        return view('livewire.modifications.actions.create-modification');
    }
}
