<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\BatteryForm;
use App\Models\Site\Battery;

class CreateBattery extends Component
{
    public BatteryForm $form;
    public array $categories = [];


    public function mount()
    {
        $this->categories = [
            'New',
            'Tested',
            'Used'
        ];
    }
    public function create()
    {
        $validated = $this->validate();

        Battery::create($this->form->all());



        Toaster::success('Inserted Successfully');
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.sites.actions.create-battery');
    }
}
