<?php

namespace App\Livewire\Modifications;

use App\Models\Modification\Modification;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use WireUi\Traits\WireUiActions;

class ModificationLinks extends Component
{
    use WireUiActions;

    public $site;


    public $modification;


    public function delete()
    {
        //  dd($this->modification);



        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Delete this modification?',
            'acceptLabel' => 'Yes, delete it',
            'method' => 'deleteModification',
            'params' => 'Saved',
        ]);
    }

    public function deleteModification()

    {

        if ($this->modification->modification_status_id == 2) {/////////modification still in progress
            $this->modification->reservation->update(['status' => 'cancelled']); ////////cancel the reservation
            $this->modification->reservation->po->decrement('in_progress', $this->modification->reservation->amount); ///////decrement the po in progress amount by the reserved amount
            $this->modification->reservation->po->increment('on_hand', $this->modification->reservation->amount); //////increment the po on hand amount to be used by other users
            $this->modification->delete();
        }


        Toaster::success('Modification deleted Successfully');

        return redirect()->route('site.modifications', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.modifications.modification-links');
    }
}
