<?php

namespace App\Models;

use App\Enums\Areas;
use App\Models\Site\Modification;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'

    ];

   

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'area_user')
            ->withPivot(['zone_id', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class);
    }


    // Get users with specific role in this team
    public function getUsersWithRole(string $roleName)
    {
        return $this->users()->whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName)
                ->where('area_id', $this->id);
        })->get();
    }

     // Get team manager
    public function getTeamManager()
    {
        return $this->getUsersWithRole($this->code . '_team_manager')->first();
    }
}
