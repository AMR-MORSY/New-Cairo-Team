<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requester extends Model
{
    
     protected $hidden=['created_at','updated_at'];

     public function modifications():HasMany
     {
        return  $this->hasMany(Modification::class);

     }
}
