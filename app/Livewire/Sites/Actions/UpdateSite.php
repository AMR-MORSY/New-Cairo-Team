<?php

namespace App\Livewire\Sites\Actions;

use Toaster;
use App\Enums\Teams;
use App\Enums\Zones;
use Livewire\Component;
use App\Models\Site\Site;
use Masmerise\Toaster\Toast;
use App\Livewire\Forms\SiteForm;


class UpdateSite extends Component
{
    public SiteForm $form;
    public $site;

    public $zones, $teams;


    public  $previousUrl;
    public function mount(Site $site)
    {
        $this->authorize('update', $site);
        $this->previousUrl = url()->previous();
        $this->site = $site;
        $this->zones = Zones::cases();
        $this->teams = Teams::cases();

        $this->form->setSite($site);
    }

    public function update()
    {

        $this->form->isUpdate = true;

        $validated = $this->validate();
        $this->site->update($validated);

        Toaster::success('Updated Successfully');


        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    public function goBack()
    {

        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
