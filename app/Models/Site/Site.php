<?php

namespace App\Models\Site;

use Dom\Attr;
use App\Enums\Host;
use App\Enums\Areas;
use App\Enums\Guest;
use App\Enums\Zones;
use App\Models\Area;
use App\Models\Team;
use App\Models\Zone;
use App\Enums\Status;
use App\Enums\SiteTypies;
use App\Enums\SiteSharing;
use App\Models\Site\Cascade;
use App\Policies\SitePolicy;
use App\Enums\SiteCategories;
use App\Enums\SiteSeverities;
use App\Models\Site\SiteData;
use App\Models\Site\SiteNote;
use App\Models\Site\PowerData;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modification\Modification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;

#[UsePolicy(SitePolicy::class)]
class Site extends Model
{

    protected $appends = ['nodal_name', 'nodal_code','zone_name','team_name'];
    protected $table = 'sites';

    protected $hidden = ["created_at", 'updated_at'];

    protected $guarded = [];




    protected function siteCode(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return strtoupper($value);
            }
        );
    }

    protected function siteName(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return strtoupper($value);
            }
        );
    }


    protected function getNodalNameAndCode($query)
    {
        $nodal = Cascade::where('cascade_code', $this->site_code)->first();
        if ($nodal) {

            $nodalSite = $this->where('site_code', $nodal->nodal_code)->first();

            if ($query == 'name') {
                return $nodalSite->site_name;
            } else {
                return $nodalSite->site_code;
            }
        }
        return null;
    }

    protected function nodalName(): Attribute
    {
        return  Attribute::make(
            get: function () {
                return $this->getNodalNameAndCode('name');
            }
        );
    }

    protected function nodalCode(): Attribute
    {
        return  Attribute::make(
            get: function () {
                return $this->getNodalNameAndCode('code');
            }
        );
    }


    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    
    protected function zoneName(): Attribute
    {
        return  Attribute::make(
            get: function () {
                return $this->zone->code;
            }
        );
    }
    protected function teamName(): Attribute
    {
        return  Attribute::make(
            get: function () {
                return $this->team->code;
            }
        );
    }



    public function modifications(): HasMany
    {
        return $this->hasMany(Modification::class, 'site_code','site_code');
    }


    public function cascades(): HasMany
    {
        return $this->hasMany(Cascade::class, 'nodal_code', 'site_code'); //// because we do not follow the name convention and replaced site_id reference with nodal_code as the reference is the site code not the id, so we have to mention both different foreign and reference   
    }

    public function batteries():HasMany
    {
        return $this->hasMany(Battery::class,'site_code','site_code');
    }


    public function power_data():HasOne
    {
        return $this->hasOne(PowerData::class,'site_code','site_code');
    }
     public function site_data():HasOne
    {
        return $this->hasOne(SiteData::class,'site_code','site_code');
    }

    public function notices():HasMany
    {
        return $this->hasMany(SiteNote::class,'site_code','site_code');
    }
}
