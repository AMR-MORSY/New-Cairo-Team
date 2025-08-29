<?php

namespace App\Livewire\Sites\Actions;

use App\Models\Area;
use App\Models\Zone;
use Livewire\Component;
use App\Models\Site\Site;
use App\Livewire\Forms\SiteForm;

class UpdateSite extends Component
{
    public SiteForm $form;
    public $site;

     public $zones,$areas;

  

    public function mount(Site $site)
    {
        $this->site=$site;
         $this->zones=Zone::all();
        $this->areas=Area::all();

        $this->form->setSite($site);

    }

    public function update()
    {
        // $this->form->zone_id=$this->site->zone_id;
        //  $this->form->area_id=$this->site->area_id;
         $this->form->isUpdate=true;
         $this->validate();

       


    }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
