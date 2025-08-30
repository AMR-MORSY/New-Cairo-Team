<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;

class ModificationStatus extends Model
{
    protected $table='modification_status';

    protected $hidden=['created_at','updated_at'];
}
