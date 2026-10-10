<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Battery extends Model
{

  protected $table = 'batteries';

  protected $hidden = ["created_at", 'updated_at'];

  protected $fillable = [
    "site_code",
    "batteries_brand",
    "stock",
    "comment",
    "category",
    "battery_volt",
    "battery_amp_hr",
    "no_strings",
    "batteries_status",
    "installation_date",
    "theft_case"
  ];

  public function site(): BelongsTo
  {
    return $this->belongsTo(Site::class, 'site_code', 'site_code');
  }
}
