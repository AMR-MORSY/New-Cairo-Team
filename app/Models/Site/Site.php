<?php

namespace App\Models\Site;

use App\Models\Site\Cascade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $table = 'sites';

    protected $hidden = ["created_at", 'updated_at'];

    protected $guarded = [];


    public function cascades(): HasMany
    {
        return $this->hasMany(Cascade::class, 'nodal_code', 'site_code'); //// because we do not follow the name convention and replaced site_id reference with nodal_code as the reference is the site code not the id, so we have to mention both different foreign and reference   
    }
}
