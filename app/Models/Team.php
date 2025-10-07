<?php

namespace App\Models;

use App\Enums\Areas;
use App\Models\User;
use App\Models\Zone;
use App\Models\Site\Site;
use App\Models\Site\Modification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active'

    ];

   public function sites():HasMany
   {
    return $this->hasMany(Site::class);
   }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class,'team_user')
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
                ->where('team_id', $this->id);
        })->get();
    }

     // Get team manager
    public function getTeamManager()
    {
        return $this->getUsersWithRole($this->code . '_team_manager')->first();
    }
}
