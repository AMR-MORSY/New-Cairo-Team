<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Livewire\Forms\SiteDataForm;

class SiteData extends Component
{
    public SiteDataForm $form;
    public object $site;

    public ?object $siteData;


    public string $page="site.noSiteData";
    public function mount(Site $site)
    {
         $this->authorize('viewSiteProperties', $site);
        $this->site = $site;
        $this->siteData = $this->site->site_data;

      
        if ($this->siteData) {
            $this->form->setSiteData($this->siteData);
            $this->form->adjustBooleanValues();

            $this->page="site.siteData";

        }
    }

    public function render()
    {
        return view('livewire.sites.actions.site-data');
    }
}
