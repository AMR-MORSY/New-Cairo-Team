<?php

namespace App\Livewire\Modifications\Actions;

use Toaster;
use Carbon\Carbon;
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
        // dd($this->form->all());

        $this->validate();

        if ($this->form->modification_status_id == 1 or $this->form->modification_status_id == 3) {
            Toaster::error('Modification status is considered done or waiting D6 after quotation submission')->duration('7000');
            return;
        }
        $this->form->inprogressFormSubmission();

        // $project = Project::find($this->form->project_id);
        // $projectPOName = $project->getProjectPOName();
        // $subcontractor = Subcontractor::find($this->form->subcontractor_id);

        // $POs = $subcontractor->getSubcontractorAvailablePOs($projectPOName);

        // if (count($POs) > 0) {
        //     $onHands = $this->form->checkPOOnHandAmount($POs); //////array of POs on hand amount
        //     if (count($onHands) > 0) {
        //         // dd($onHands);
        //         $modification = Modification::create(
        //             $this->form->all()
        //         );

        //         $po = PurchaseOrder::find($onHands[0]['id']);
        //         $estCostFloatValue = floatval(str_replace(',', '', $this->form->est_cost));



        //         $po->increment('in_progress', $estCostFloatValue);
        //         $po->decrement('on_hand', $estCostFloatValue);

        //         $modification->actions()->attach($this->form->action_id);
        //         $expiresAt = Carbon::now()->addDays(intval(env('MODIFICATION_EXPIRATION_PERIOD', 20)));
        //         $modificationReservation = ModificationReservation::create([
        //             'modification_id' => $modification->id,
        //             'purchase_order_id' => $onHands[0]['id'],
        //             'status' => 'active',
        //             'amount' => $this->form->est_cost,
        //             'reserved_at' => now(),
        //             'expires_at' => $expiresAt,

        //         ]);
        //         Toaster::success('Modification created Successfully');

        //         return redirect()->route('modification.details', $modification->id);
        //     } else {
        //         $subcontractorName = $subcontractor->name;
        //         Toaster::error("There is no available POs with sufficient amount to cover this modification for . $subcontractorName ");
        //     }
        // } else {
        //     $subcontractorName = $subcontractor->name;
        //     Toaster::error("There is no available POs for . $subcontractorName ");

        //     return;
        // }
    }
    public function render()
    {
        return view('livewire.modifications.actions.create-modification');
    }
}
