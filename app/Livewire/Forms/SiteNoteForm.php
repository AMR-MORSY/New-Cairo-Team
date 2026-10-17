<?php

namespace App\Livewire\Forms;

use App\Models\Site\SiteNote;
use Livewire\Form;
use App\Enums\SiteNoticeTypes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Enum;

class SiteNoteForm extends Form
{




    public $id = '';
    public $site_code = '';
    public $title = '';
    public $site_notice_type_id = '';
    public $description = '';
    public $is_solved = 0;

    public string $context = "create";

    public ?object $siteNote;


    public function setSiteNote($siteNote)
    {

        $this->siteNote = $siteNote;
        $this->context = "update";
        $this->id = $siteNote->id;
        $this->site_code = $siteNote->site_code;
        $this->title = $siteNote->title;
        $this->site_notice_type_id = $siteNote->site_notice_type_id;
        $this->description = $siteNote->description;
        $this->is_solved = $siteNote->is_solved;
    }



    public function setSiteCode($site)
    {
        $this->site_code = $site->site_code;
    }

    /**
     * Active entries only - except, on update, the ones this modification
     * already uses, so a retired subcontractor doesn't block saving it.
     */
    private function activeLookup(string $table, array $keep = []): \Illuminate\Validation\Rules\Exists
    {
        return Rule::exists($table, 'id')->where(
            fn($q) => $q->where('is_active', true)->when($keep, fn($q) => $q->orWhereIn('id', $keep))
        );
    }

    public function rules()
    {
        $current = $this->context === 'update' ? SiteNote::find($this->id) : null;
        $rules = [
            "site_code" => ["required", "exists:sites,site_code"],
            "title" => ["required", "string", 'max:100'],
            "description" => ["required", 'string', 'max:300'], ////////////////////chars,special chars,spaces,numbers,underscores,dashes,tabs, and new lines
            "is_solved" => ["required", "in:0,1"],
            "site_notice_type_id" => ["required", $this->activeLookup('site_notice_types', $current ? [$current->site_notice_type_id] : [])],


        ];

        if ($this->context == "update") {
            $rules["id"] = ["required", "exists:site_notices,id"];
        }


        return $rules;
    }
}
