<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class BatteryForm extends Form
{
    public bool $isUpdate = false;

    public     $battery;
    public     $batteries_brand = '';
    public     $installation_date = null;
    public     $no_strings = '';
    public     $category = '';
    public     $stock = '';
    public     $comment = '';
    public     $theft_case = null;
    public     $batteries_status = '';
    public     $battery_volt = '';
    public     $battery_amp_hr = '';
    public     $site_code = '';


    public function setBattery($battery)
    {
        $this->battery = $battery;

        $this->batteries_brand = $battery->batteries_brand;
        $this->installation_date = $battery->installation_date;
        $this->no_strings = $battery->no_strings;
        $this->category = $battery->category;
        $this->stock = $battery->stock;
        $this->comment = $battery->comment;
        $this->theft_case = $battery->theft_case;
        $this->batteries_status = $battery->batteries_status;
        $this->battery_volt = $battery->battery_volt;
        $this->battery_amp_hr = $battery->battery_amp_hr;
        $this->site_code = $battery->site_code;
    }

    public function setSiteCode($site_code)
    {
        $this->site_code=$site_code;
    }

    public function rules()
    {
        $rules = [
            "site_code" => ['required', "exists:sites,site_code"],
            "batteries_brand" =>  ["required", 'max:50'],
            "stock" =>  ["nullable", 'max:50'],
            "comment" => ["nullable", 'max:250'],
            "category" => ["required", 'regex:/^Used|New|Tested$/'],
            "battery_volt" =>  ['nullable', 'max:50'],
            "battery_amp_hr" =>  ['nullable', 'max:50'],
            "no_strings" =>  ["required", 'integer', 'max:100'],
            "batteries_status" =>  ['nullable', 'max:50'],
            "installation_date" => ["requiredIf:theft_case,null","date"],
            "theft_case" => ["nullable", "date"],

        ];

        if ($this->isUpdate) {


            $rules['site_code'] = ["required", "exists:sites,site_code", Rule::unique('sites', 'site_code')->ignore($this->battery)];
        }

        return $rules;
    }
}
