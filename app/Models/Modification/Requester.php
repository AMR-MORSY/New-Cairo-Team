<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\IsLookupOption;

class Requester extends Model
{
    use IsLookupOption;

    
     protected $hidden=['created_at','updated_at'];

     /** Maintained through the admin panel, so both columns are assignable. */
     protected $fillable = ['name', 'is_active'];

     protected $casts = ['is_active' => 'boolean'];


     public function modifications():HasMany
     {
        return  $this->hasMany(Modification::class);

     }
}
