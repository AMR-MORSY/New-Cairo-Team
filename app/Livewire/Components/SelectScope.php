<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Collection;

class SelectScope extends Component
{
     public function __construct(public Collection $scopes, public string $selected)
    {
        //
    }

    public function render()
    {
        return view('livewire.components.select-scope');
    }
}
