<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteData;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Title;
use App\Livewire\Forms\SiteDataForm;
use Illuminate\Database\UniqueConstraintViolationException;

#[Title('create Site Data')]
class CreateSiteData extends Component
{

    public SiteDataForm $form;



    public Site $site;

    public string $page="siteData.create";
    public function mount(Site $site)
    {

        $this->authorize('create', Site::class);

        $this->form->setSiteCode($site->site_code);
        $this->site = $site;

        // a site has one site data record (Site::site_data is a hasOne)
        if ($site->site_data()->exists()) {
            return $this->toExistingRecord();
        }
    }




    public function newSiteData()
    {
        $values = $this->form->validatedForSave();

        if ($this->site->site_data()->exists()) {
            return $this->toExistingRecord();
        }

        try {
            SiteData::create([...$values, 'site_code' => $this->site->site_code]);
        } catch (UniqueConstraintViolationException) {
            // a second click that raced the first past the check above
            return $this->toExistingRecord();
        }

        Toaster::success('Inserted Successfully');
        return redirect()->route('site.siteData', ['site' => $this->site->site_code]);
    }

    private function toExistingRecord()
    {
        Toaster::info('This site already has site data; update it instead.');

        return redirect()->route('site.siteData', ['site' => $this->site->site_code]);
    }

    public function render()
    {
        return view('livewire.sites.actions.create-site-data');
    }
}
