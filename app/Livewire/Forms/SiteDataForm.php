<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class SiteDataForm extends Form
{
    public bool $isUpdate = false;
    public $site_code = '';
    public $on_air_date = '';
    public $topology = '';
    public $structure = '';
    public $equip_room = '';
    public $address = '';
    public $x_coordinate = '';
    public $y_coordinate = '';
    public $ntra_cluster = "0";
    public $care_ceo = "0";
    public $axis = "0";
    public $serve_compound = "0";
    public $universities = "0";
    public $hot_spot = "0";
    public $network_type = '';
    public $last_pm_date = '';
    public $need_access_permission="0";
    public $permission_type = '';

    public $siteData;


    public function setSiteData($siteData)
    {
        $this->siteData = $siteData;

        $this->site_code = $siteData->site_code;
        $this->on_air_date = $siteData->on_air_date;
        $this->topology = $siteData->topology;
        $this->structure = $siteData->structure;
        $this->equip_room = $siteData->equip_room;
        $this->address = $siteData->address;
        $this->x_coordinate = $siteData->x_coordinate;
        $this->y_coordinate = $siteData->x_coordinate;
        $this->ntra_cluster = $siteData->ntra_cluster;
        $this->care_ceo = $siteData->care_ceo;
        $this->axis = $siteData->axis;
        $this->serve_compound = $siteData->erve_compound;
        $this->universities = $siteData->universities;
        $this->hot_spot = $siteData->hot_spot;
        $this->network_type = $siteData->network_type;
        $this->last_pm_date = $siteData->last_pm_date;
        $this->need_access_permission=$siteData->need_access_permission;
        $this->permission_type = $siteData->permission_type;
    }
    public function setSiteCode($site_code)
    {
        $this->site_code = $site_code;
    }

    public function rules()
    {
        $rules = [
            'site_code' => ['required', 'exists:sites,site_code'],
            "on_air_date" => ['nullable', 'date'],
            "topology" => ['nullable', 'max:50', 'string'],
            "structure" => ['nullable', 'max:50', 'string'],
            "equip_room" => ['nullable', 'max:50', 'string'],
            "ntra_cluster" =>   ['nullable', 'in:0,1'],
            "care_ceo" =>  ['nullable', 'in:0,1'],
            "axis" =>  ['nullable', 'in:0,1'],
            "serve_compound" =>   ['nullable', 'in:0,1'],
            "universities" => ['nullable', 'in:0,1'],
            "hot_spot" => ['nullable', 'in:0,1'],
            "x_coordinate" => ['nullable', 'max:55', 'regex:/^[0-9.]+$/'],
            "y_coordinate" => ['nullable', 'max:55', 'regex:/^[0-9.]+$/'],
            "address" => ['nullable', 'max:255', 'string'],
            "network_type" => ['nullable', 'max:55', 'regex:/^[a-zA-Z0-9 \/]+$/'],
            "last_pm_date" => ['nullable', 'date'],
            "need_access_permission" => ['nullable', 'in:0,1'],
            "permission_type" => ['nullable', 'max:55', 'string'],

        ];

        if ($this->isUpdate) {


            $rules['site_code'] = ["required", "exists:sites,site_code", Rule::unique('site_data', 'site_code')->ignore($this->siteData)];
        }

        return $rules;
    }
}
