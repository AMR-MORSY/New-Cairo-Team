<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Concerns\IsLookupOption;

class Action extends Model
{
    use IsLookupOption;


     protected $hidden=['created_at','updated_at'];

     /** Maintained through the admin panel, so both columns are assignable. */
     protected $fillable = ['name', 'is_active'];

     protected $casts = ['is_active' => 'boolean'];

    



     public function modifications():BelongsToMany
    {
        return $this->belongsToMany(Modification::class);
    }
}
