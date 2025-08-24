<?php

namespace App\Models;

use App\Enums\Zones;
use App\Models\Area;
use App\Models\Site\Modification;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';
    protected $fillable = ['team_id', 'name', 'code', 'description', 'is_active'];

    // protected $casts = [

    //     "name" => Zones::class

    // ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot(['area_id', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class);
    }

    // Get zone manager
    public function getZoneManager()
    {
        $roleName = $this->area->code . '_zone_manager';
        return $this->area->users()
            ->wherePivot('zone_id', $this->id)
            ->whereHas('roles', function ($query) use ($roleName) {
                $query->where('name', $roleName)
                    ->where('area_id', $this->area_id);
            })->first();
    }
}
