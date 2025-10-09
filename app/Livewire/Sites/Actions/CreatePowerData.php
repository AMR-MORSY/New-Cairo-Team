<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\PowerData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\PowerDataForm;

class CreatePowerData extends Component
{
     public PowerDataForm $form;
   
    public $site;
    public function mount(Site $site)
    {
        

        $this->form->setSiteCode($site->site_code);
        $this->site=$site;
    }
    public function newPowerDataRecord()
    {
        $validated = $this->validate();

        $powerData = PowerData::create($validated);



        Toaster::success('Inserted Successfully');
        $this->form->reset();
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-power-data');
    }
}
