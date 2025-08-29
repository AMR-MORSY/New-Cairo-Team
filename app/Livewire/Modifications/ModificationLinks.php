<?php

namespace App\Livewire\Modifications;

use Livewire\Component;

class ModificationLinks extends Component
{
    public $site,$modifications;
    public function render()
    {
        return view('livewire.modifications.modification-links');
    }
}
