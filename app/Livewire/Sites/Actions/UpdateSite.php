<?php

namespace App\Livewire\Sites\Actions;

use Toaster;
use App\Models\Area;
use App\Models\Team;
use App\Models\Zone;
use Livewire\Component;
use App\Models\Site\Site;
use Masmerise\Toaster\Toast;
use App\Livewire\Forms\SiteForm;
use WireUi\Traits\WireUiActions;


class UpdateSite extends Component
{
    use WireUiActions;
    public SiteForm $form;
    public $site;

    public $zones, $teams;



    public function mount(Site $site)
    {
        $this->site = $site;
        $this->zones = Zone::all();
        $this->teams = Team::all()->take(3);/////////////////HGLI,AGLI,NGLI only

        $this->form->setSite($site);
    }

    public function update()
    {

        $this->form->isUpdate = true;
      
        $validated = $this->validate();
        $this->site->update($validated);

        Toaster::success('Updated Successfully');
      

        return redirect()->route('site.show',['site'=>$this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
