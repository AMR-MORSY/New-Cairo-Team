<?php

namespace App\Livewire\Sites\Actions;

use App\Models\Site\Site;
use Livewire\Component;

class ShowBattery extends Component
{
    public $site;

    public $batteries;

    public function mount(Site $site)
    {
        $this->site=$site;
        $this->batteries=$this->site->batteries;
        if(count($this->batteries)>0) 
        {
            $this->dispatch('site_batteries',batteries:$this->batteries);
        }

    }
    public function render()
    {
        return view('livewire.sites.actions.show-battery');
    }
}
