<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;


class SearchResultSites extends ModalComponent
{
    public $props;
    public function mount($props)
    {
        
        $this->props=$props;

    }
    public function render()
    {
        return view('livewire.modals.search-result-sites');
    }
}
