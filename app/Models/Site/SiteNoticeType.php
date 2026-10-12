<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Model;

class SiteNoticeType extends Model
{ use \App\Models\Concerns\IsLookupOption;
    protected $table = 'site_notice_types';

    protected $hidden = ['created_at', 'updated_at'];

    /** Maintained through the admin panel, so both columns are assignable. */
    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
