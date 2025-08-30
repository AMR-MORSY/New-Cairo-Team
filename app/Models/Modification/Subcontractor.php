<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{
   protected $table='subcontractors';

    protected $hidden=['created_at','updated_at'];
}
