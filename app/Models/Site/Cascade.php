<?php

namespace App\Models\Site;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cascade extends Model
{
     protected $table='cascades';

    protected $hidden=["created_at",'updated_at'];

    protected $guarded=[];

    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,'nodal_code','site_code');
    }
}
