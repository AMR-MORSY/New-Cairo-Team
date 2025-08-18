<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use App\Models\Site\Site;
use App\Contracts\SiteInterface;
use Livewire\Attributes\Layout;

class SiteDetails extends Component
{


    protected $siteRepository;

    public  $site;

    public $newDirectCascades;

    public $indirectCascades;

    public function mount(SiteInterface $siteRepositry, Site $site)
    {
        $this->siteRepository = $siteRepositry;


        $this->site = $site;
        $directCascades = $site->cascades;

        $this->newDirectCascades = $this->siteRepository->directCascades($directCascades);



        $this->indirectCascades = $this->siteRepository->indirectCascades($directCascades);
        $this->indirectCascades = collect($this->indirectCascades);




        // return view('pages.sites.searchingSitesResults', ['site'=>$site,'directCascades' => $newDirectCascades,'indirectCascades'=>$indirectCascades]);
    }

    public function render()
    {
        // return view('livewire.sites.site-details', ['site' => $this->site, 'directCascades' => $this->newDirectCascades, 'indirectCascades' => $this->indirectCascades]);
        return view('livewire.sites.site-details');
    }
}
