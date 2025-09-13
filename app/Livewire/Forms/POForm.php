<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Enums\ModificationPOs;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use App\Rules\CommaSeparatedNumber;

class POForm extends Form
{
    public string $po_number = '';

    public int|string $subcontractor_id="";

    public string $type = '';

    public  $amount;
   
    public string $status="";



      public function rules()
    {
        $rules = [
            "po_number" => ['required', 'string','max:50'],
            "subcontractor_id" => ["required", "exists:subcontractors,id"],
            "type" => ["required",Rule::enum(ModificationPOs::class)],
            "amount" => ['required', new CommaSeparatedNumber],
            "status" => ["required",Rule::in(['closed', 'open'])],
           

        ];

        return $rules;
    }
}
