<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Enums\SiteNoticeTypes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Enum;

class SiteNoteForm extends Form
{
    

   

    public $site_code='';
    public $title='';
    public $notice_type='';
    public $description='';
    public $is_solved=0;


    public function setSiteNote($siteNote)
    {
        
        $this->site_code=$siteNote->site_code;
        $this->title=$siteNote->title;
        $this->notice_type=$siteNote->notice_type;
        $this->description=$siteNote->description;
        $this->is_solved=$siteNote->is_solved;
    }


  
    public function setSiteCode($site)
    {
        $this->site_code=$site->site_code;
    }

     public function rules()
    {
        $rules = [
            "site_code" => ["required", "exists:sites,site_code"],
            "title" => ["required","string",'max:100'],
            "description" => ["required",'string','max:300'],////////////////////chars,special chars,spaces,numbers,underscores,dashes,tabs, and new lines
            "is_solved" => ["required", "in:0,1"],
            "notice_type" => ["required", new Enum(SiteNoticeTypes::class)],
            

        ];
     

        return $rules;
    }


}
