<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;

class UnpricedItem extends Model
{
     protected $table="unpriced_items";

    protected $hidden =['created_at','updated_at'];

}
