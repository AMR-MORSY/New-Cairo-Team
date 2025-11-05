<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Repositories\CairoEastSitesRepository;

class CeVipSites extends Component
{
    public $sites;
    public function mount(CairoEastSitesRepository $cairo_east_sites_repository)
    {  $this->authorize('viewCESites',Site::class);
     
         $sites = Site::with('cascades', 'batteries')->where('zone_id', 2)->where(function($query){
            $query->where('category','VIP')->orWhere('category','VIP + NDL')->orWhere('category','VIP + NDL + G.SQR');
         })->get();
        $this->sites = $cairo_east_sites_repository::dispatchSites($sites);
    }
    public function render()
    {
        return view('livewire.sites.actions.ce-vip-sites');
    }
}
