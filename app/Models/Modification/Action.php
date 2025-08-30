<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Action extends Model
{

     protected $hidden=['created_at','updated_at'];
    



     public function modifications():BelongsToMany
    {
        return $this->belongsToMany(Modification::class);
    }
}
