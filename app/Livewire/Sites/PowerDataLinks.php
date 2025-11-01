<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Masmerise\Toaster\Toaster;

class PowerDataLinks extends Component
{
    public $site;
    public $powerData;

    public function delete()
    {
        $this->authorize('update', $this->site);
        $this->powerData->delete();

        Toaster::info('Deleted Successfully.');

        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.power-data-links');
    }
}
