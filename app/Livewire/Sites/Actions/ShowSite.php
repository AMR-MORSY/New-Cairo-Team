<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Contracts\SiteInterface;
use App\Livewire\Forms\SiteForm;

class ShowSite extends Component
{

    public SiteForm $form;
    protected $siteRepository;

    public  $site;

    public $newDirectCascades;

    public $indirectCascades;

    public function mount(SiteInterface $siteRepositry, Site $site)
    {
        $this->siteRepository = $siteRepositry;

        $this->form->setSite($site);


        $this->site = $site;
        $directCascades = $site->cascades;

        $this->newDirectCascades = $this->siteRepository->directCascades($directCascades);



        $this->indirectCascades = $this->siteRepository->indirectCascades($directCascades);
        $this->indirectCascades = collect($this->indirectCascades);
    }
    public function render()
    {
        return view('livewire.sites.actions.show-site');
    }
}
