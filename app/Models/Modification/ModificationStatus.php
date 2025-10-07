<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModificationStatus extends Model
{
    protected $table='modification_status';

    protected $hidden=['created_at','updated_at'];

        public function modifications():HasMany
     {
        return  $this->hasMany(Modification::class);

     }
}
