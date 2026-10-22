<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\Title;
use App\Contracts\SiteInterface;
use App\Repositories\SiteRepository;
    #[Title('CE Sites')]
class CeSites extends Component
{


    public $sites;


    public function mount()
    {
        $this->authorize('viewCESites',Site::class);
    }

   
  
    public function render()
    {
        return view('livewire.sites.actions.ce-sites');
    }
}
