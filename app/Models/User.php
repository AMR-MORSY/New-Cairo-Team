<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Area;
use App\Models\Site\Modification;
use App\Models\Zone;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $casts = [

        "login_at" => "datetime:Y-m-d H:i:s"

    ];

    public function areas():BelongsToMany
    {
        return $this->belongsToMany(Area::class,'area_user')
            ->withPivot(['zone_id', 'joined_at', 'status',])
            ->withTimestamps();
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class,'area_user')
            ->withPivot(['area_id', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class, 'created_by');
    }

    // Get user's area and zone
    public function getAreaAndZone()
    {
        $teamUser = $this->areas()->first();
       
        if ($teamUser) {
            return [
                'area' => $teamUser,
                'zone' => Zone::find($teamUser->pivot->zone_id)
            ];
        }
        return ['area' => null, 'zone' => null];
    }

    // Check if user is team manager
    public function isTeamManager(Area $area): bool
    {
        return $this->hasRole($area->code . '_team_manager', $area);
    }

    // Check if user is zone manager
    public function isZoneManager(Zone $zone): bool
    {
        return $this->hasRole($zone->area->code . '_zone_manager', $zone->area);
    }

    // Check if user is site engineer
    public function isSiteEngineer(Area $area): bool
    {
        return $this->hasRole($area->code . '_product_engineer', $area);
    }

    // Get user's zone in a specific team
    public function getZoneInArea(Area $area): ?Zone
    {
        $teamUser = $this->areas()->where('areas.id', $area->id)->first();
        return $teamUser && $teamUser->pivot->zone_id ?
            Zone::find($teamUser->pivot->zone_id) : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
