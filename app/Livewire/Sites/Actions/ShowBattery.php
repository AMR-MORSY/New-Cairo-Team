<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\BatteryForm;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use Livewire\Attributes\Title;
use Livewire\Component;

 #[Title('Show Battery')]
class ShowBattery extends Component
{
   public BatteryForm $form;

    public Site $site;
    public Battery $battery;

    public string $page='show';

    public function mount(Battery $battery)
    {
          $this->authorize('viewSiteProperties', $battery->site);
        $this->site = $battery->site;
        $this->battery = $battery;
        $this->form->setBattery($battery);

    }
    public function render()
    {
        return view('livewire.sites.actions.show-battery');
    }
}
