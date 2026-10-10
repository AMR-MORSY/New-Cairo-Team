<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;

class SiteImages extends Component
{
  
    public $site;
    public function mount(Site $site)
    {
         $this->authorize('viewSiteProperties', $site);
        $this->site=$site;
      

        
    }
    public function render()
    {
        return view('livewire.sites.actions.site-images');
    }
}
