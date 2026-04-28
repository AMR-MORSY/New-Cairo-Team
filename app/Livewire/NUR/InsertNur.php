<?php

namespace App\Livewire\NUR;

use Livewire\Component;

class InsertNur extends Component
{
    public $technology;

    public function mount($technology)
    {
        $this->technology=$technology;
    }
    public function render()
    {
        return view('livewire.n-u-r.insert-nur');
    }
}
