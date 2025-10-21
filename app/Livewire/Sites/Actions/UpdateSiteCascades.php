<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Contracts\SiteInterface;
use Toaster;

class UpdateSiteCascades extends Component
{
    public  $site;
    public $cascades;
    public function mount(SiteInterface $siteRepository, Site $site)
    {
        $this->site = $site;

        $this->cascades = $siteRepository->directCascades($site->cascades);
    }

    #[On('removeCascade')]
    public function deleteCascade($site_code)
    {
        $this->site->cascades()->where('nodal_code', $this->site->site_code)->where('cascade_code', $site_code)->delete();
        return redirect()->route('site.cascades.update', ['site' => $this->site->site_code]);
    }

    #[On('addSiteToCascades')]

    public function addSiteToCascades($site_code)
    {
        if ($site_code == $this->site->site_code) {
            $this->dispatch('closeModal');
            Toaster::error('Can not cascade on itself');
        } elseif ($this->site->cascades()->where('cascade_code', $site_code)->exists()) {
            $this->dispatch('closeModal');
            Toaster::error('Already cascaded');
        } else {

            $this->site->cascades()->create([
                'cascade_code' => $site_code,
                'nodal_code' => $this->site->site_code,
                'cascade_name' => Site::where('site_code', $site_code)->first()->site_name


            ]);
            return redirect()->route('site.cascades.update', ['site' => $this->site->site_code]);
        }
    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-cascades');
    }
}
