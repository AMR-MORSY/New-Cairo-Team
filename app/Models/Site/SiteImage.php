<?php

namespace App\Models\site;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteImage extends Model
{
    protected $table = 'site_images';
    protected $hidden = ['created_at'];

     protected $fillable = [
        'site_code',
        "path",
       
    ];

    
    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,'site_code',"site_code");
    }
}
