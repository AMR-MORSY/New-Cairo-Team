<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\PowerData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\PowerDataForm;

class UpdatePowerData extends Component
{
     public PowerDataForm $form;
    public $powerData;
 
    public $site;
    public function mount(PowerData $powerData)
    {
        $this->powerData = $powerData;
        $this->site=$powerData->site;
        $this->form->setPowerData($powerData);
    }

    public function updatePowerData()
    {
        $validated = $this->form->validate();

        $battery = $this->powerData->update($validated);

        Toaster::success('Updated Successfully');
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-power-data');
    }
}
