<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface SiteInterface
{
    public function all();

  
    ////////////this function returns site's direct cascades
    public function directCascades(Collection $directCascades):array;
    ////////////this function returns site's indirect cascades
    public function indirectCascades(object $indirectCascades):array;

  
    public function create();
}
