<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Battery;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\BatteryForm;

class UpdateBattery extends Component
{
    public BatteryForm $form;
    public $battery;
    public $categories;
    public $site;
    public function mount(Battery $battery)
    {
        $this->battery = $battery;
        $this->site=$battery->site;

        $this->categories = [
            'New',
            'Tested',
            'Used'
        ];

        $this->form->setBattery($battery);
    }

    public function updateBatteryRecord()
    {
        $validated = $this->form->validate();

        $battery = $this->battery->update($validated);

        Toaster::success('Updated Successfully');
        return redirect()->route('battery.show', ['battery' => $this->battery->id]);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-battery');
    }
}
