<?php

namespace App\Livewire\Sites;

use Livewire\Component;

class SiteNotesLinks extends Component
{
    public $site;

    public string $page;
    public function render()
    {
        return view('livewire.sites.site-notes-links');
    }
}
