<?php

namespace App\Livewire;

use Livewire\Component;

class ModalManager extends Component
{
    public $component = '';
    public $arguments = [];
    public $show = false;

    protected $listeners = ['openModal', 'closeModal'];

    public function openModal($component, $arguments = [])
    {
        $this->component = $component;
        $this->arguments = $arguments;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->component = '';
         $this->arguments = [];
    }
    public function render()
    {
        return view('livewire.modal-manager');
    }
}
