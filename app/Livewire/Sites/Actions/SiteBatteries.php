<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Site;

#[Title('Site Batteries')]
class SiteBatteries extends Component
{
    public object $site;

    public ?object $batteries;

    public string $page='site.batteries';

    public function mount(Site $site)
    {
        // the signed-in user is implicit; anything passed here lands on the
        // policy AFTER $user, and Gate picks the policy from the first
        // argument -- a User sent it looking for a UserPolicy, found none,
        // and denied everyone
        $this->authorize('viewSiteProperties', $site);
        $this->site = $site;
        $this->batteries = $this->site->batteries;
    }
    public function render()
    {
        return view('livewire.sites.actions.site-batteries');
    }
}
