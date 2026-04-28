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
  
    
    public function mount( Site $site)
    {
        
        $this->site = $site;

       
    }

    #[On('removeCascade')]
    public function deleteCascade($site_code)
    {
        $this->site->cascades()->where('nodal_code', $this->site->site_code)->where('cascade_code', $site_code)->delete();
        return redirect()->route('site.cascades.update', ['site' => $this->site->site_code]);
    }
    
    public function render()
    {
        return view('livewire.sites.actions.update-site-cascades');
    }
}
