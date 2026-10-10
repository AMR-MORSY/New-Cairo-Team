<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Modification\Modification;

class SiteModifications extends Component
{

    public $modifications;
    public $site;
    public function mount(Site $site)
    {
        $this->authorize('viewSiteModifications', $site);
        
        $this->site=$site;
        $this->modifications = $site->modifications()->with([
            "project",
            'subcontractor',
            'actions',
            'requester',
            'actionOwner',
            'reservation'
        ])->get();

       
       

       
    }

    
    public function render()
    {
        return view('livewire.modifications.actions.site-modifications', ['site' => $this->site, 'modifications' => $this->modifications]);
    }
}
