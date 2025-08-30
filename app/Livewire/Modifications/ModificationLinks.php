<?php

namespace App\Livewire\Modifications;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use WireUi\Traits\WireUiActions;

class ModificationLinks extends Component
{
    use WireUiActions;
    // public $site;
    public $modification;

    public $site_code;

    public function delete()
    {
        $this->site_code = $this->modification->site_code;


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
        $this->modification->delete();

        Toaster::success('Modification deleted Successfully');

        return redirect()->route('site.modifications', ['site' => $this->site_code]);
    }
    public function render()
    {
        return view('livewire.modifications.modification-links');
    }
}
