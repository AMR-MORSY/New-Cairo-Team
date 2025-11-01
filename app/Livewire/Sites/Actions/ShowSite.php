<?php

namespace App\Livewire\Sites\Actions;

use Toaster;
use App\Models\User;
use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\MuxPlan;
use App\Contracts\SiteInterface;
use App\Livewire\Forms\SiteForm;

class ShowSite extends Component
{

    public SiteForm $form;
    protected $siteRepository;

    public  $site;

    public $newDirectCascades;

    public $muxPlans;

    public $indirectCascades;

    public  $previousUrl;

    public function mount(SiteInterface $siteRepositry, Site $site)
    {

         $this->previousUrl = url()->previous();

        $this->siteRepository = $siteRepositry;

        $this->form->setSite($site); ////this function binds the $site model with the form which contains the site model attributes which could be accessed inside the view 'livewire.sites.actions.show-site' by using the object form

        $this->form->zone_id = $site->zone->code; //////override form->zone_id to show the zone name instead of zone id
        $this->form->team_id = $site->team->code; ////////like zone_id

        $this->site = $site;
        $directCascades = $site->cascades;

        $this->newDirectCascades = $this->siteRepository->directCascades($directCascades);



        $this->indirectCascades = $this->siteRepository->indirectCascades($directCascades);
        $this->indirectCascades = collect($this->indirectCascades);
        //  dd([$this->newDirectCascades,$this->indirectCascades]);
    }

      public function goBack()
    {
        
        return redirect()->to($this->previousUrl);
    }

    public function muxPlan()
    {
        $this->authorize('viewSiteProperties',$this->site);
        $site_code = $this->site->site_code;
        $muxPlans = MuxPlan::where('ne', 'like', "%$site_code%")->get();
        if (count($muxPlans) > 0) {

            $this->muxPlans = $muxPlans;
            $this->dispatch('mux_plans', mux_plans: $muxPlans);
        } elseif (!count($muxPlans) > 0) {
            $muxPlans = MuxPlan::where('fe', 'like', "%$site_code%")->get();
            if (count($muxPlans) > 0) {
                $this->muxPlans = $muxPlans;
                $this->dispatch('mux_plans', mux_plans: $muxPlans);
            }
            else{
                Toaster::error('No Mux Plans available');
            }
        } else {
             Toaster::error('No Mux Plans available');
        }
    }

    public function render()
    {
        return view('livewire.sites.actions.show-site');
    }
}
