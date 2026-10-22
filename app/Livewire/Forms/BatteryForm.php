<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class BatteryForm extends Form
{
    /** The values of the batteries.category enum column. */
    public const CATEGORIES = ['New', 'Tested', 'Used'];

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
            // the regex /^Used|New|Tested$/ let "Brand New" through: | split the whole pattern
            "category" => ["required", Rule::in(self::CATEGORIES)],
            "battery_volt" =>  ['nullable', 'max:50'],
            "battery_amp_hr" =>  ['nullable', 'max:50'],
            "no_strings" =>  ["required", 'integer', 'min:1', 'max:100'],
            "batteries_status" =>  ['nullable', 'max:50'],
            "installation_date" => ["required_without:theft_case", "date"],
            "theft_case" => ["nullable", "date"],

        ];

      

        return $rules;
    }

    /**
     * Validated values ready to save. A field cleared in the browser comes
     * back as '', which MySQL strict mode refuses in a DATE column, so
     * blanks become null. site_code is left out: it is a public property the
     * browser can change, so callers take it from the site they loaded.
     */
    public function validatedForSave(): array
    {
        $validated = $this->validate();

        unset($validated['site_code']);

        return array_map(fn($value) => $value === '' ? null : $value, $validated);
    }
}
