<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\PowerDataForm;
use Livewire\Component;
use App\Models\Site\Site;

class SitePowerData extends Component
{
    public PowerDataForm $form;
    public $site;

    public $powerData;


    public function mount(Site $site)
    {
        $this->site = $site;
        $this->powerData = $this->site->power_data;
        if ($this->powerData) {
            $this->form->setPowerData($this->powerData);
        }
    }


    public function render()
    {
        return view('livewire.sites.actions.site-power-data');
    }
}
