<?php

namespace App\Livewire\Sites\Actions;

use Toaster;
use App\Enums\Teams;
use App\Enums\Zones;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Site;
use Masmerise\Toaster\Toast;
use App\Livewire\Forms\SiteForm;

#[Title('Update Site')]
class UpdateSite extends Component
{
    public SiteForm $form;
    public Site $site;

    public array $zones;
    public array $teams;
    public string $page = "site.update";

    // public  $previousUrl;
    public function mount(Site $site)
    {
        $this->authorize('update', $site);
        // $this->previousUrl = url()->previous();
        $this->site = $site;
        $this->zones = Zones::cases();
        $this->teams = Teams::cases();

        $this->form->setSite($site);
    }

    public function update()
    {

        $this->form->isUpdate = true;

        $this->site->update($this->form->validatedForSave());

        Toaster::success('Updated Successfully');


        return redirect()->route('site.show', ['site' => $this->site->site_code]);
    }
    // public function goBack()
    // {

    //     return redirect()->to($this->previousUrl);
    // }
    public function render()
    {
        return view('livewire.sites.actions.update-site');
    }
}
