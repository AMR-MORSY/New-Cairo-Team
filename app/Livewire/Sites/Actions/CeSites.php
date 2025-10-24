<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\Title;
use App\Contracts\SiteInterface;
use App\Repositories\SiteRepository;

class CeSites extends Component
{

    #[Title('CE Sites')]
    public $sites;

    private function dispatchSites($siteRepository)
    {

        $this->sites =  $this->sites->map(function ($nodal) use ($siteRepository) {
            $nodal->battery_brand = $nodal->batteries ? $nodal->batteries->pluck('batteries_brand')->implode("\n") : '';
            $nodal->no_strings = $nodal->batteries ? $nodal->batteries->pluck('no_strings')->implode("\n") : '';
            $nodal->count_cascades = count($siteRepository->indirectCascades($nodal->cascades))  + $nodal->cascades->count();
            $nodal->installation_date = $nodal->batteries ? $nodal->batteries->pluck('installation_date')->implode("\n") : '';
            return $nodal;
        });

        

        $this->dispatch('nodal_sites', sites: $this->sites);
    }

    public function CEVIPSites(SiteInterface $siteRepository)
    {
         $this->sites = Site::with('cascades', 'batteries')->where('zone_id', 2)->where(function($query){
            $query->where('category','VIP')->orWhere('category','VIP + NDL')->orWhere('category','VIP + NDL + G.SQR');
         })->get();

        $this->dispatchSites($siteRepository);

    }
     public function CEGSquareSites(SiteInterface $siteRepository)
    {
         $this->sites = Site::with('cascades', 'batteries')->where('zone_id', 2)->where(function($query){
            $query->where('category','G.SQR')->orWhere('category','VIP + G.SQR')->orWhere('category','NDL + G.SQR')->orWhere('category','VIP + NDL + G.SQR');
         })->get();

        $this->dispatchSites($siteRepository);

    }
    public function CETPSites(SiteInterface $siteRepository)
    {


        $this->sites = Site::with('cascades', 'batteries')->where('zone_id', 2)->where('site_name', 'like', '%3P%')->get();

        $this->dispatchSites($siteRepository);
    }
    public function CENodalSites(SiteInterface $siteRepository)
    {
     
        $this->sites = Site::with('cascades', 'batteries')->whereHas('cascades', function ($query) {
            $query->where('zone_id', 2);
        })->get();

        $this->dispatchSites($siteRepository);
    }
    public function render()
    {
        return view('livewire.sites.actions.ce-sites');
    }
}
