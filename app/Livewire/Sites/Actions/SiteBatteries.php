<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;

class SiteBatteries extends Component
{
    public $site;

    public $batteries;

    public function mount(Site $site)
    {
        $this->site = $site;
        $this->batteries = $this->site->batteries;

       
    }
    public function render()
    {
        return view('livewire.sites.actions.site-batteries');
    }
}
