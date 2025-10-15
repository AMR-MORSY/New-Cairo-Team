<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Livewire\Forms\SiteDataForm;

class SiteData extends Component
{
    public SiteDataForm $form;
    public $site;

    public $siteData;


    public function mount(Site $site)
    {
        $this->site = $site;
        $this->siteData = $this->site->site_data;
        if ($this->siteData) {
            $this->form->setSiteData($this->siteData);
        }
    }

    public function render()
    {
        return view('livewire.sites.actions.site-data');
    }
}
