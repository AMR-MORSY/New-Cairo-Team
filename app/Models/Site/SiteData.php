<?php

namespace App\Models\Site;

use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteData extends Model
{

    protected $table='site_data';
    protected $hidden=['created_at','updated_at'];

    protected $fillable = [
        'site_code',
        "on_air_date",
        "topology",
        "structure",
        "equip_room",
        "address",
        "x_coordinate",
        "y_coordinate",
        "ntra_cluster",
        "care_ceo",
        "axis",
        "serve_compound",
        "universities",
        "hot_spot",
        "network_type",
        "last_pm_date",
        "permission_type",
    ];


    public function site():BelongsTo
    {
        return $this->belongsTo(Site::class,'site_code',"site_code");
    }
}
