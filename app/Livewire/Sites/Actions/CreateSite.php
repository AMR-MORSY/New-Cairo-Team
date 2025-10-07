<?php

namespace App\Livewire\Sites\Actions;

use App\Models\Area;
use App\Models\Zone;
use Livewire\Component;
use App\Models\Site\Site;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\SiteForm;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateSite extends Component
{
    public SiteForm $form;

    public $zones, $teams;

    public function mount()
    {

        $this->zones = Zone::all();
        $this->teams = Team::all();
    }

    public function create()
    {
        $validated = $this->validate();

     
        Site::create([
            "site_name" => $this->form->site_name,
            "site_code" => $this->form->site_code,
            "RNC" => $this->form->RNC,
            "BSC" => $this->form->BSC,
            "severity" => $this->form->severity,
            "category" => $this->form->category,
            "office" => $this->form->office,
            "type" => $this->form->type,
            "sharing" => $this->form->sharing,
            "host" => $this->form->host,
            "team_id" => $this->form->team_id,
            "zone_id" => $this->form->zone_id,
            "gest" => $this->form->gest,
            "cells_2G" => $this->form->cells_2G,
            "cells_3G" => $this->form->cells_3G,
            "cells_4G" => $this->form->cells_4G,
            "status" => $this->form->status,
            "we_code" => $this->form->we_code,
            "vf_code" => $this->form->vf_code,
            "et_code" => $this->form->et_code
        ]);

        Toaster::success('Inserted Successfully');
        $this->form->reset();
        // if ($this->validate()) {

        //     Toaster::error('some fields in the form have errors');

        // }
    }
    public function render()
    {
        return view('livewire.sites.actions.create-site');
    }
}
