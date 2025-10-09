<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use WireUi\Traits\WireUiActions;
use App\Models\Modification\Modification;

class SiteModifications extends Component
{
    use WireUiActions;

    public $modifications;
    public $site;
    public function mount(Site $site)
    {
        
        $this->site=$site;
        $this->modifications = $site->modifications()->with([
            "project",
            'subcontractor',
            'actions',
            'requester',
            'actionOwner',
            'modification_status',
            'team',
            'zone',
            'reservation'
        ])->get();

       
       

       
    }

    
    public function render()
    {
        return view('livewire.modifications.actions.site-modifications', ['site' => $this->site, 'modifications' => $this->modifications]);
    }
}
