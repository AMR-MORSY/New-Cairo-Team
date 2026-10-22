<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\BatteryForm;

#[Title('Update Battery')]
class UpdateBattery extends Component
{
    public BatteryForm $form;
    public Battery $battery;
    public array $categories;
    public Site $site;

    public string $page='update.battery';
    public function mount(Battery $battery)
    {
         $this->authorize('update', $battery->site);
        $this->battery = $battery;
        $this->site=$battery->site;

        $this->categories = BatteryForm::CATEGORIES;

        $this->form->setBattery($battery);
    }

    public function updateBatteryRecord()
    {
        // the battery keeps its site; validatedForSave() leaves site_code out
        $this->battery->update($this->form->validatedForSave());

        Toaster::success('Updated Successfully');
        return redirect()->route('battery.show', ['battery' => $this->battery->id]);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-battery');
    }
}
