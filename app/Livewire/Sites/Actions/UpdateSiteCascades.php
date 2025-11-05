<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Contracts\SiteInterface;
use App\Models\Site\Cascade;
use Toaster;

class UpdateSiteCascades extends Component
{
    public  $site;
    public $cascades;
    public $previousUrl;
    public function mount(SiteInterface $siteRepository, Site $site)
    {
        $this->previousUrl = url()->previous();
        $this->site = $site;

        $this->cascades = $siteRepository->directCascades($site->cascades);
    }

    #[On('removeCascade')]
    public function deleteCascade($site_code)
    {
        $this->site->cascades()->where('nodal_code', $this->site->site_code)->where('cascade_code', $site_code)->delete();
        return redirect()->route('site.cascades.update', ['site' => $this->site->site_code]);
    }
    public function goBack()
    {

        return redirect()->to($this->previousUrl);
    }

    public function render()
    {
        return view('livewire.sites.actions.update-site-cascades');
    }
}
