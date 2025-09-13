<?php

namespace App\Livewire\Modifications\Components;

use Toaster;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\POForm;
use App\Models\Modification\PO;
use Illuminate\Support\Collection;
use App\Models\Modification\Subcontractor;

class ViewPOs extends Component
{
    public POForm $form;

    public Collection $subcontractors;

    public Collection|null $POs = null;


    public function mount()
    {
        $this->subcontractors = Subcontractor::all();
    }

    public function searchPOs()
    {
        if ($this->form->subcontractor_id == null) {
            Toaster::error('Choose Subcontractor.');
            return;
        } elseif ($this->form->po_number != null) {
            $this->POs = PO::with('subcontractor')->where('subcontractor_id', $this->form->subcontractor_id)->where('po_number', $this->form->po_number)->get();
        } elseif ($this->form->type != null) {
            $this->POs = PO::with('subcontractor')->where('subcontractor_id', $this->form->subcontractor_id)->where('type', $this->form->type)->get();
        } elseif ($this->form->status != null) {
            $this->POs = PO::with('subcontractor')->where('subcontractor_id', $this->form->subcontractor_id)->where('status', $this->form->status)->get();
        } else {
            $this->POs = PO::with('subcontractor')->where('subcontractor_id', $this->form->subcontractor_id)->get();
        }

        if (count($this->POs) > 0) {
            $this->dispatch('showPos', $this->POs);
        } else {
            Toaster::error('No POs found');
        }
    }


    #[On('removePO')]

    public function removePO($id)
    {

        $this->POs = $this->POs->filter(function ($value) use ($id) {
            return $value->id != $id;
        });


        $this->dispatch('showPos', $this->POs);
    }
    public function render()
    {
        return view('livewire.modifications.components.view-p-os', ['subcontractors' => $this->subcontractors, 'POs' => $this->POs]);
    }
}
