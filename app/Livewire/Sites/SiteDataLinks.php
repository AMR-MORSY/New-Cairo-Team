<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Masmerise\Toaster\Toaster;

class SiteDataLinks extends Component
{
    public $site;
    public $siteData;

    public function delete()
    {
        $this->authorize('update',$this->site);
        $this->siteData->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.site-data-links');
    }
}
