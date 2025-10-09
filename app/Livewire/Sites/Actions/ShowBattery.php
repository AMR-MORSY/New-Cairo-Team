<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\BatteryForm;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use Livewire\Component;

class ShowBattery extends Component
{
   public BatteryForm $form;

    public $site;
    public $battery;

    public function mount(Battery $battery)
    {
        $this->site = $battery->site;
        $this->battery = $battery;
        $this->form->setBattery($battery);

    }
    public function render()
    {
        return view('livewire.sites.actions.show-battery');
    }
}
