<?php

namespace App\Models\Site;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PowerData extends Model
{
    protected $table = 'site_power_data';

    protected $hidden = ["created_at", 'updated_at'];

    protected $fillable = [
        "site_code",
        "power_source",
        "power_meter_type",
        "gen_config",
        "gen_serial",
        "gen_capacity",
        "overhaul_consumption",
        "c_length",
        "c_size",

    ];

    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,'site_code','site_code');
    }
}
