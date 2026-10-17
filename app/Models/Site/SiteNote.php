<?php

namespace App\Models\Site;

use App\Models\Concerns\IsLookupOption;
use Attribute;
use Carbon\Carbon;
use App\Models\Site\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteNote extends Model
{
    protected $table = 'site_notices';
    protected $hidden = ['updated_at'];

    use IsLookupOption;


    protected $fillable = [
        'site_code',
        "site_notice_type_id",
        "title",
        "description",
        "is_solved",
    ];


    public function getFormattedCreatedAtAttribute()
    {
        $timezone = optional(Auth::user())->time_zone ?? 'Africa/Cairo'; ////The optional() helper is a Laravel utility function that provides safe navigation through potentially null objects.
        /////for instance if the Auth::user() object is null then error occurred because the app try to access "time_zone" property on null. By using optional method optional(Auth::user())->time_zone, returns null when both the object Auth::user() or time_zone is null  
        return $this->attributes['created_at']
            ? Carbon::parse($this->attributes['created_at'])->setTimezone($timezone)->toCookieString()
            : null;
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_code', "site_code");
    }

    public function siteNoticeType(): BelongsTo
    {
        return $this->belongsTo(SiteNoticeType::class, 'site_notice_type_id', 'id');
    }
}
