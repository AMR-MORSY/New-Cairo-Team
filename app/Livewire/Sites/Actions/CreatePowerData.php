<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Site;
use App\Models\Site\PowerData;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\PowerDataForm;

#[Title('create Power Data')]
class CreatePowerData extends Component
{
     public PowerDataForm $form;
   
    public Site $site;

    public string $page="powerData.create";
    public function mount(Site $site)
    {
         $this->authorize('create', Site::class);

        $this->form->setSiteCode($site->site_code);
        $this->site=$site;
    }
    public function newPowerDataRecord()
    {
        PowerData::create([
            ...$this->form->validatedForSave(),
            'site_code' => $this->site->site_code,
        ]);

        Toaster::success('Inserted Successfully');
        return redirect()->route('site.powerData', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-power-data');
    }
}
