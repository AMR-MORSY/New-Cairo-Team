<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Toaster;

class BatteriesLinks extends Component
{
    public $site;
    public $battery;

    public function delete()
    {
        $this->authorize('update', $this->site);
        $this->battery->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.batteries', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.batteries-links');
    }
}
