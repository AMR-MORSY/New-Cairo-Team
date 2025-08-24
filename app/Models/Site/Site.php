<?php

namespace App\Models\Site;

use App\Enums\Host;
use App\Enums\Areas;
use App\Enums\Guest;
use App\Enums\Zones;
use App\Enums\Status;
use App\Enums\SiteTypies;
use App\Enums\SiteSharing;
use App\Models\Site\Cascade;
use App\Enums\SiteCategories;
use App\Enums\SiteSeverities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{

    protected $appends = ['nodal_name', 'nodal_code'];
    protected $table = 'sites';

    protected $hidden = ["created_at", 'updated_at'];

    protected $guarded = [];


    // protected $casts = [
    //     "severity" => SiteSeverities::class,
    //     'type' => SiteTypies::class,
    //     'category' => SiteCategories::class,
    //     'status' => Status::class,
    //     "area" => Areas::class,
    //     'zone' => Zones::class,
    //     'host' => Host::class,
    //     'gest' => Guest::class,
    //     'sharing' => SiteSharing::class
    // ];


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


    public function cascades(): HasMany
    {
        return $this->hasMany(Cascade::class, 'nodal_code', 'site_code'); //// because we do not follow the name convention and replaced site_id reference with nodal_code as the reference is the site code not the id, so we have to mention both different foreign and reference   
    }
}
