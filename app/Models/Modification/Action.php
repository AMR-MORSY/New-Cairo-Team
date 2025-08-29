<?php

namespace App\Models\Modification;

use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Action extends Model
{
    



     public function modifications():BelongsToMany
    {
        return $this->belongsToMany(Modification::class);
    }
}
