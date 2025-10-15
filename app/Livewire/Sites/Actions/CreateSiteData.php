<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\SiteDataForm;

class CreateSiteData extends Component
{
    public SiteDataForm $form;





    public $site;
    public function mount(Site $site)
    {


        $this->form->setSiteCode($site->site_code);
        $this->site = $site;
    }
    public function newBatteryRecord()
    {
        $validated = $this->validate();

        $battery = SiteData::create($validated);



        Toaster::success('Inserted Successfully');
        $this->form->reset();
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-site-data');
    }
}
