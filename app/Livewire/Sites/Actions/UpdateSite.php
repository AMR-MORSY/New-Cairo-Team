<?php

namespace App\Livewire\Sites\Actions;

use App\Models\Area;
use App\Models\Zone;
use Livewire\Component;
use App\Models\Site\Site;
use App\Livewire\Forms\SiteForm;
use App\Models\Team;

class UpdateSite extends Component
{
    public SiteForm $form;
    public $site;

     public $zones,$teams;

  

    public function mount(Site $site)
    {
        $this->site=$site;
         $this->zones=Zone::all();
        $this->teams=Team::all();

        $this->form->setSite($site);

    }

    public function update()
    {
       
         $this->form->isUpdate=true;
         $this->validate();

       


    }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
