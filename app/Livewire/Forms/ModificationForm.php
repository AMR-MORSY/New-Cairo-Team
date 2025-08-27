<?php

namespace App\Livewire\Forms;

use App\Models\Modification\ModificationStatus;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Subcontractor;
use Livewire\Form;
use App\Models\Site\Site;
use Auth;
use Livewire\Attributes\Validate;
use Illuminate\Database\Eloquent\Collection;

class ModificationForm extends Form
{
   
    public $site_code='';
    public $subcontractor_id;
    public $pending = "";
    public $est_cost = 0;
    public $final_cost = 0;
    public $request_date = '';
    public $requester_id = null;
    public $project_id = null;
    public $modification_status_id = null;
    public $cw_date = '';
    public $d6_date = '';
    public $description = "";
    public $reported = 0;
    public $reported_at = '';
    public $zone_id = null;
    public $area_id = null;
    public $action_owner = null;


    public function setModificationDefaultAttributes($site)
    {
        $this->site_code=$site->site_code;

        $this->action_owner=Auth::user()->id;

        $this->zone_id=$site->zone_id;

        $this->area_id=$site->area_id;

       
        

    }


}
