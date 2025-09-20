<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Collection;
use App\Models\Modification\Action;
use App\Models\Modification\OverPo;
use App\Models\Modification\Invoice;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Livewire\Forms\ModificationForm;
use App\Models\Modification\Modification;
use App\Models\Modification\OverPoInvoice;
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

    public $site;

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

        $this->modification = $modification;

        $this->site = $modification->site;

        $modificationActions = $modification->actions->pluck('id');
        $modification->action_id = $modificationActions->toArray();

        $this->form->setModificationDetails($modification);
    }

    private function manipulatePO($quotationAmount, $reservedAmount)
    {
        $this->modification->reservation->po->decrement('in_progress', $reservedAmount);
        $this->modification->reservation->po->increment('on_hand', $reservedAmount);
        $this->modification->reservation->po->increment('invoiced', $quotationAmount);
        $this->modification->reservation->po->decrement('on_hand', $quotationAmount);
    }
    private function adjustPO($quotationAmount, $reservedAmount)
    {
        if ($quotationAmount <= $reservedAmount) { ////////////////////////////////////////////////quotation amount might be less or equal the reserved amount/in progress. in this case every thing is going normal with the PO
            $this->manipulatePO($quotationAmount, $reservedAmount);
            $Invoice = Invoice::create([
                'modification_reservation_id' => $this->modification->reservation->id,
                'amount' => $quotationAmount,
                'reserved_at' => now(),
            ]);
        } else { ////////////////////////////////////////////////quotation amount might be greater. problem starts to appear

            $quotationReservedDiff = $quotationAmount - $reservedAmount;
            $onHand = $this->modification->reservation->po->getAvailableAmount();
            if ($quotationReservedDiff <= $onHand) /////// in this case we do not have a problem with the PO
            {
                $this->manipulatePO($quotationAmount, $reservedAmount);
                $Invoice = Invoice::create([
                    'modification_reservation_id' => $this->modification->reservation->id,
                    'amount' => $quotationAmount,
                    'reserved_at' => now(),
                ]);
            } else { ////////////////when the on hand does not cover the diff amount, we will leave the on hand as it is and put this quotation in over po invoices table
                $overPo = OverPoInvoice::create([
                    'modification_reservation_id' => $this->modification->reservation->id,
                    'amount' => $quotationAmount,
                    'reserved_at' => now(),
                ]);
            }
        }
    }
    public function update()
    {
        $this->validate();

        $quotation = $this->modification->quotation()->where('is_active', 0)->first(); //////////check if there is an inactive quotation for this modification 
        $activeQuotation = $this->modification->quotation()->where('is_active', 0)->first(); //////////check if there is an active quotation for this modification 

        if ($this->form->modification_status_id == 1 || $this->form->modification_status_id == 3) { /////////////////done or waiting D6

            if ($quotation) {
                $quotationAmount = $quotation->sumMailListItems() + $quotation->sumPriceListItems();
                if ($this->modification->reservation->status == 'active') //////////////////means that modification was in progress and the user submit quotation and want to update the modification to done/waiting D6
                {

                    $reservedAmount = $this->modification->reservation->amount;
                    dd($quotationAmount);
                    $this->adjustPO($quotationAmount, $reservedAmount);
                } elseif ($this->modification->reservation->status == 'expired') {
                    $onHand = $this->modification->reservation->po->getAvailableAmount();
                    if ($quotationAmount <= $onHand) {
                        $this->modification->reservation->po->increment('invoiced', $quotationAmount);
                        $this->modification->reservation->po->decrement('on_hand', $quotationAmount);
                        $Invoice = Invoice::create([
                            'modification_reservation_id' => $this->modification->reservation->id,
                            'amount' => $quotationAmount,
                            'reserved_at' => now(),
                        ]);
                    } else {
                        $overPo = OverPoInvoice::create([
                            'modification_reservation_id' => $this->modification->reservation->id,
                            'amount' => $quotationAmount,
                            'reserved_at' => now(),
                        ]);
                    }
                }
                $this->modification->quotation->update(['is_active' => 1]);
                $this->modification->reservation->update(['status' => 'completed']);
                $this->modification->update(
                    $this->form->all()
                );

                $this->modification->actions()->sync($this->form->action_id); // Replace all existing relationships with the new array
                Toaster::success('Modification updated Successfully');

                return redirect()->route('modification.details', $this->modification->id);
            } elseif ($activeQuotation) {
                $this->modification->update(
                    $this->form->all()
                );

                $this->modification->actions()->sync($this->form->action_id); // Replace all existing relationships with the new array
                Toaster::success('Modification updated Successfully');

                return redirect()->route('modification.details', $this->modification->id);
            } else {
                return redirect()->back()->with('quotation_error', 'You have to submit Quotation');
            }
        } elseif ($this->form->modification_status_id == 2) /////////////in progress
        {
            if ($quotation) {

                


                $this->modification->update(
                    $this->form->all()
                );

                $this->modification->actions()->sync($this->form->action_id); // Replace all existing relationships with the new array
                Toaster::success('Modification updated Successfully');

                return redirect()->route('modification.details', $this->modification->id);
            }
        }
    }
    public function render()
    {
        return view('livewire.modifications.actions.update-modification');
    }
}
