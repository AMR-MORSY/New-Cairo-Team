<?php

namespace App\Livewire\Modifications\Components;

use Livewire\Component;
use App\Livewire\Forms\POForm;
use Masmerise\Toaster\Toaster;
use App\Models\Modification\PO;
use App\Models\Modification\PurchaseOrder;
use Illuminate\Support\Collection;
use App\Models\Modification\Subcontractor;

class CreatePO extends Component
{
    public POForm $form;


    public Collection $subcontractors;


    public function mount()
    {
        $this->subcontractors = Subcontractor::all();
    }


    public function create()
    {

        $this->validate();

        $po = PurchaseOrder::create($this->form->all());

        if ($po) {
              Toaster::success('Po created Successfully');

              $this->form->reset();
        }
      
    }


    public function render()
    {
        return view('livewire.modifications.components.create-p-o', ['subcontractors' => $this->subcontractors]);
    }
}
