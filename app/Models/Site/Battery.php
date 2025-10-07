<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Battery extends Model
{
   
    protected $table = 'batteries';

    protected $hidden = ["created_at", 'updated_at'];

   public function site():BelongsTo
   {
     return $this->belongsTo(Site::class,'site_code','site_code');
   }

}
