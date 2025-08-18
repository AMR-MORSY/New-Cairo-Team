<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Site\Site;
use Livewire\Attributes\Validate;

class SiteForm extends Form
{

    public  $site_name = '';
    public  $site_code = '';
    public  $RNC = '';
    public  $BSC = '';
    public  $severity = '';
    public  $category = '';
    public  $office = '';
    public  $type = '';
    public  $sharing = '';
    public  $host = '';
    public  $oz = '';
    public  $zone = '';
    public  $gest = '';
    public  $cells_2G = '';
    public  $cells_3G = '';
    public  $cells_4G = '';
    public  $status = '';
    public  $we_code = '';
    public  $vf_code = '';
    public  $et_code = '';

    public function setSite(Site $site)
    {
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
        $this->oz = $site->oz;
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
}
