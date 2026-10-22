<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Site;
use App\Models\Site\Battery;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\BatteryForm;
     
#[Title('create Battery')]
class CreateBattery extends Component
{

    public BatteryForm $form;
    public array $categories = [];




    public Site $site;

    public string $page='create';
    public function mount(Site $site)
    {
        $this->authorize('create', Site::class);
        $this->categories = BatteryForm::CATEGORIES;

        $this->form->setSiteCode($site->site_code);
        $this->site = $site;
    }
    public function newBatteryRecord()
    {
        $battery = Battery::create([
            ...$this->form->validatedForSave(),//validatedForSave() also leaves site_code out of the saved values. Create takes it from the loaded $this->site, and update leaves the battery on its own site. 
            'site_code' => $this->site->site_code,
        ]);

        Toaster::success('Inserted Successfully');
        return redirect()->route('battery.show', ['battery' => $battery->id]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-battery');
    }
}
