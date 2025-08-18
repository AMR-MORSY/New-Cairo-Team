<?php

namespace App\Livewire\Sites;

use Livewire\Component;

class SearchingForSite extends Component
{
    public $code;

    public function show()
    {
        

        return redirect()->route('site.show',['site'=>$this->code]);
       

    }

}
