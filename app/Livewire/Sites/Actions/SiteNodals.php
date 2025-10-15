<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\Title;
use App\Contracts\SiteInterface;

class SiteNodals extends Component
{

    #[Title('Nodal Sites')]
    public $nodalSites;
    // protected $siteRepository;
    // public function mount(SiteInterface $siteRepository)
    // {
    //     $this->siteRepository = $siteRepository;

    // }
    public function CENodalSites(SiteInterface $siteRepository)
    {
        // dd($this->siteRepository);
        $this->nodalSites = Site::with('cascades', 'batteries')->whereHas('cascades', function ($query) {
            $query->where('zone_id', 2);
        })->get();

        $this->nodalSites =  $this->nodalSites->map(function ($nodal) use ($siteRepository) {
            $nodal->battery_brand = $nodal->batteries ? $nodal->batteries->pluck('batteries_brand')->implode("\n") : '';
            $nodal->no_strings = $nodal->batteries ? $nodal->batteries->pluck('no_strings')->implode("\n") : '';
            $nodal->count_cascades = count($siteRepository->indirectCascades($nodal->cascades))  + $nodal->cascades->count();
            $nodal->installation_date = $nodal->batteries ? $nodal->batteries->pluck('installation_date')->implode("\n") : '';
            return $nodal;
        });

        $this->dispatch('nodal_sites', nodalSites: $this->nodalSites);
    }
    public function render()
    {
        return view('livewire.sites.actions.site-nodals');
    }
}
