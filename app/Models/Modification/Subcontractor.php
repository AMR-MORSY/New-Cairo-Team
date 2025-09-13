<?php

namespace App\Models\Modification;

use App\Models\Modification\PO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcontractor extends Model
{
   protected $table='subcontractors';

    protected $hidden=['created_at','updated_at'];


    public function pos():HasMany
    {
        return $this->hasMany(PO::class);
    }
}
