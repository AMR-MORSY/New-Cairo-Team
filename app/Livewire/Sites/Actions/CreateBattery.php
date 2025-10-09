<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\Battery;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\BatteryForm;

class CreateBattery extends Component
{
    public BatteryForm $form;
    public array $categories = [];




    public $site;
    public function mount(Site $site)
    {
        $this->categories = [
            'New',
            'Tested',
            'Used'
        ];

        $this->form->setSiteCode($site->site_code);
        $this->site=$site;
    }
    public function newBatteryRecord()
    {
        $validated = $this->validate();

        $battery = Battery::create($validated);



        Toaster::success('Inserted Successfully');
        $this->form->reset();
        return redirect()->route('battery.show', ['battery' => $battery->id]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-battery');
    }
}
