<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Repositories\CairoEastSitesRepository;

class CeGSquareSites extends Component
{
    public $sites;
    public function mount()
    {
          $this->authorize('viewCESites',Site::class);

      
    }
    public function render()
    {
        return view('livewire.sites.actions.ce-g-square-sites');
    }
}
