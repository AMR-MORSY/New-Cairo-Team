<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class SiteCreationForm extends Component
{
    public bool $readonly = false;
    public string $target = '';



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


    public function mount(bool $readonly, object $site, string $target)
    {

        $this->readonly = $readonly;
        $this->target = $target;
        if ($site) {

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

    public function updateSite() {
        
    }
    public function createSite() {}
    public function render()
    {
        return view('livewire.sites.site-creation-form');
    }
}
