<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;

class CeBatteries extends Component
{
    public function mount()
    {
        $this->authorize('viewCESites', Site::class);
    }
    public function render()
    {
        return view('livewire.sites.actions.ce-batteries');
    }
}
