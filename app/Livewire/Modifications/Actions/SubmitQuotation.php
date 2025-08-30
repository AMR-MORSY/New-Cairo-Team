<?php

namespace App\Livewire\Modifications\Actions;

use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SubmitQuotation extends Component
{
    public object|null $quotation;

    public function mount(Modification $modification)
    {
        $this->quotation=$modification->quotation;

       
    }
    public function render()
    {
        return view('livewire.modifications.actions.submit-quotation');
    }
}
