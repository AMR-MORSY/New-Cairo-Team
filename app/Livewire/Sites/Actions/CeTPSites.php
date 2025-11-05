<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Repositories\CairoEastSitesRepository;

class CeTPSites extends Component
{
    public $sites;
    public function mount(CairoEastSitesRepository $cairo_east_sites_repository)
    {
        $this->authorize('viewCESites', Site::class);
        $sites = Site::with('cascades', 'batteries')->where('zone_id', 2)->where('site_name', 'like', '%3P%')->get();
        $this->sites = $cairo_east_sites_repository::dispatchSites($sites);
    }

    public function render()
    {
        return view('livewire.sites.actions.ce-t-p-sites');
    }
}
