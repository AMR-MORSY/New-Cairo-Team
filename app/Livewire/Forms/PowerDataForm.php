<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class PowerDataForm extends Form
{
    public bool $isUpdate = false;

    public $site_code;
    public $power_source;
    public $power_meter_type;
    public $gen_config;
    public $gen_serial;
    public $gen_capacity;
    public $overhaul_consumption;
    public $c_length;
    public $c_size;

    public $powerData;

    public function setPowerData($powerData)
    {
        $this->powerData = $powerData;

        $this->site_code = $powerData->site_code;
        $this->power_source = $powerData->power_source;
        $this->power_meter_type = $powerData->power_meter_type;
        $this->gen_config = $powerData->gen_config;
        $this->gen_serial = $powerData->gen_serial;
        $this->gen_capacity = $powerData->gen_capacity;
        $this->overhaul_consumption = $powerData->overhaul_consumption;
        $this->c_length = $powerData->c_length;
        $this->c_size = $powerData->c_size;
    }

    public function setSiteCode($site_code)
    {
        $this->site_code = $site_code;
    }
    public function rules()
    {
        $rules = [
            "site_code" => ['required', "exists:sites,site_code"],
            "power_source" => ['nullable', 'max:50', 'string'],
            "power_meter_type" => ['nullable', 'max:50', 'string'],
            "gen_config" => ['nullable', 'max:50', 'string'],
            "gen_serial" => ['nullable', 'max:50', 'string'],
            "gen_capacity" => ['nullable', 'max:50', 'string'],
            "c_length" => ['nullable', 'max:50', 'string'],
            "c_size" => ['nullable', 'max:50', 'string'],
            "overhaul_consumption" => ['nullable', 'max:100000', 'integer'],

        ];

        if ($this->isUpdate) {


            $rules['site_code'] = ["required", "exists:sites,site_code", Rule::unique('site_power_data', 'site_code')->ignore($this->powerData)];
        }

        return $rules;
    }
}
