<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class DynamicModal extends Component
{
    public $isOpen = false;
    public $component = null;
    public $props = [];

    // protected $listeners = [
    //     'openDynamicModal' => 'open',
    //     'closeDynamicModal' => 'close',
    //     'modalClosed' => 'close' // Listen for close events from children
    // ];

    #[On('openDynamicModal')]
    public function open($component = null, $props = [])
    {
        $this->component = $component;
        $this->props = $props;
        $this->isOpen = true;
    }
    #[On('modalClosed')]
    public function close()
    {
        $this->isOpen = false;
        $this->component = null;
        $this->props = [];

        // Emit event that modal was closed
        $this->dispatch('dynamicModalClosed');
    }
    public function render()
    {
        return view('livewire.components.dynamic-modal');
    }
}
