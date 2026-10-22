<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\SiteData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\SiteDataForm;
#[Title('Update Site Data')]
class UpdateSiteData extends Component
{
    public SiteDataForm $form;
    public SiteData $siteData;
    public object $site;

    public string $page='siteData.update';
    public function mount(SiteData $siteData)
    {
       $this->authorize('update', $siteData->site);
        $this->siteData = $siteData;
        $this->site=$siteData->site;
        $this->form->setSiteData($siteData);
    }

    public function updateSiteData()
    {
        // the record keeps its site; validatedForSave() leaves site_code out
        $this->siteData->update($this->form->validatedForSave());

        Toaster::success('Updated Successfully');
        return redirect()->route('site.siteData', ['site' => $this->site->site_code]);

    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-data');
    }
}
