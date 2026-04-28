<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Repositories\CairoEastSitesRepository;

class CeTPSites extends Component
{
    public $sites;
    public function mount()
    {
        $this->authorize('viewCESites', Site::class);
      
    }

    public function render()
    {
        return view('livewire.sites.actions.ce-t-p-sites');
    }
}
