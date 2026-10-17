<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\PowerDataForm;
use Livewire\Component;
use App\Models\Site\Site;

class SitePowerData extends Component
{
    public PowerDataForm $form;
    public object $site;

    public ?object $powerData;


    public string $page="site.noPowerData";
    public function mount(Site $site)
    {
         $this->authorize('viewSiteProperties', $site);
        $this->site = $site;
        $this->powerData = $this->site->power_data;
      
        if (isSet($this->powerData)) {
            $this->form->setPowerData($this->powerData);
            $this->page="site.powerData";
        }
    }


    public function render()
    {
        return view('livewire.sites.actions.site-power-data');
    }
}
