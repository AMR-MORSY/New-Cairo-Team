<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\SiteData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\SiteDataForm;

class UpdateSiteData extends Component
{
    public SiteDataForm $form;
    public $siteData;
    public $site;

    public function mount(SiteData $siteData)
    {
      
        $this->siteData = $siteData;
        $this->site=$siteData->site;
        $this->form->setSiteData($siteData);
    }

    public function updateSiteData()
    {
        $validated = $this->form->validate();

        $battery = $this->siteData->update($validated);

        Toaster::success('Updated Successfully');
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);

    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-data');
    }
}
