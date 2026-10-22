<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\PowerData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\PowerDataForm;

#[Title('Update Power Data')]
class UpdatePowerData extends Component
{
     public PowerDataForm $form;
    public object $powerData;
 
    public object $site;
    public string $page="powerData.update";
    public function mount(PowerData $powerData)
    {
         $this->authorize('update', $powerData->site);
        $this->powerData = $powerData;
        $this->site=$powerData->site;
        $this->form->setPowerData($powerData);
    }

    public function updatePowerData()
    {
        // the record keeps its site; validatedForSave() leaves site_code out
        $this->powerData->update($this->form->validatedForSave());

        Toaster::success('Updated Successfully');
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-power-data');
    }
}
