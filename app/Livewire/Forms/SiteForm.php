<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Enums\Host;
use App\Enums\Areas;
use App\Enums\Guest;
use App\Enums\Zones;
use App\Enums\Status;
use App\Enums\SiteTypies;
use App\Models\Site\Site;
use App\Enums\SiteSharing;
use App\Enums\SiteCategories;
use App\Enums\SiteSeverities;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Enum;


class SiteForm extends Form
{
    public Site $site;

    public  $site_name = '';
    public  $site_code = '';
    public  $RNC = '';
    public  $BSC = '';
    public  $severity = '';
    public  $category = '';
    public  $office = '';
    public  $type = '';
    public  $sharing = 'No';
    public  $host = '';
    public  $area = '';
    public  $zone = '';
    public  $gest = '';
    public  $cells_2G = 0;
    public  $cells_3G = 0;
    public  $cells_4G = 0;
    public  $status = 'On Air';
    public  $we_code = '';
    public  $vf_code = '';
    public  $et_code = '';
    public  $nodal_name = '';
    public $nodal_code = '';

    public function setSite(Site $site)
    {

        $this->nodal_name = $site->nodal_name;
        $this->nodal_code = $site->nodal_code;
        $this->site_name = $site->site_name;
        $this->site_code = $site->site_code;
        $this->RNC = $site->RNC;
        $this->BSC = $site->BSC;
        $this->severity = $site->severity;
        $this->category = $site->category;
        $this->office = $site->office;
        $this->type = $site->type;
        $this->sharing = $site->sharing;
        $this->host = $site->host;
        $this->area = $site->area;
        $this->zone = $site->zone;
        $this->gest = $site->gest;
        $this->cells_2G = $site->cells_2G;
        $this->cells_3G = $site->cells_3G;
        $this->cells_4G = $site->cells_4G;
        $this->status = $site->status;
        $this->we_code = $site->we_code;
        $this->vf_code = $site->vf_code;
        $this->et_code = $site->et_code;
    }

    public function rules()
    {
        $rules = [
            "site_code" => ["required", Rule::unique('sites', 'site_code')],
            "site_name" => ["required",  Rule::unique('sites', 'site_name'), "regex:/^([0-9a-zA-Z_-]){2,60}$/"],
            "BSC" => ["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "RNC" => ["nullable", "regex:/^([0-9a-zA-Z_-]|\s){3,50}$/"],
            "office" => ["nullable", "string", 'max:50'],
            "severity" => ['required',  new Enum(SiteSeverities::class)],
            "category" => ['required', new Enum(SiteCategories::class)],
            "type" => ['required', new Enum(SiteTypies::class)],
            "sharing" => ['required', new Enum(SiteSharing::class), function ($attribute, $value, $fail) {
                if (

                    (!empty($this->host) ||
                        !empty($this->gest)) &&
                    $value !== 'Yes'
                ) {
                    $fail('The Sharing must be "yes" when Host/Guest has a value.');
                }
            },],
            "zone" => ['required', new Enum(Zones::class)],
            "host" => ['required_if:sharing,Yes', new Enum(Host::class)],
            "gest" => ['required_if:sharing,Yes', 'different:host', new Enum(Guest::class)],
            "vf_code" => ['nullable', 'string', 'max:50'],
            "et_code" => ['nullable', 'string', 'max:50'],
            "we_code" => ['nullable', 'string', 'max:50'],
            "cells_2G" => ["nullable", "regex:/^[1-9][0-9]?$|^100$|^0$/"],
            "cells_3G" => ["nullable", "regex:/^[1-9][0-9]?$|^100$|^0$/"],
            "cells_4G" => ["nullable", "regex:/^[1-9][0-9]?$|^100$|^0$/"],
            "status" => ["required", new Enum(Status::class)],
            "area" => ['required', new Enum(Areas::class)]

        ];
        if (request()->route('site.update')) {

            $rules['site_code'] = ["required", "exists:sites,site_code", Rule::unique('sites', 'site_code')->ignore($this->site)];
        }

        return $rules;
    }
}
