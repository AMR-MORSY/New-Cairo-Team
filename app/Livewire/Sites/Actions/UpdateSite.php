<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\SiteForm;
use Livewire\Component;
use App\Models\Site\Site;

class UpdateSite extends Component
{
    public SiteForm $form;
    public $site;

    public function mount(Site $site)
    {
        $this->site=$site;

        $this->form->setSite($site);

    }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
