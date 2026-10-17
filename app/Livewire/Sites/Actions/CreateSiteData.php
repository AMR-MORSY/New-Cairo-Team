<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteData;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\SiteDataForm;
use App\Models\site\SiteImage;

class CreateSiteData extends Component
{
    public SiteDataForm $form;



    public object $site;

    public string $page="siteData.create";
    public function mount(Site $site)
    {

        $this->authorize('create', Site::class);

        $this->form->setSiteCode($site->site_code);
        $this->site = $site;
    }




    public function newSiteData()
    {
        $validated = $this->validate();

        // dd($validated);

        $battery = SiteData::create($validated);



        Toaster::success('Inserted Successfully');
        $this->form->reset();
        return redirect()->route('site.siteData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-site-data');
    }
}
