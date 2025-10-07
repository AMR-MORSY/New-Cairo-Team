<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Zones;
use App\Models\Area;
use App\Models\Team;
use App\Models\Zone;
use Illuminate\Support\Str;
use App\Models\Site\Modification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot(['zone_id', 'joined_at', 'status',])
            ->withTimestamps();
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'team_user')
            ->withPivot(['team_id', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function modifications()
    {
        return $this->hasMany(Modification::class, 'created_by');
    }

    // Get user's area and zone
    public function getTeamAndZone()
    {
        $teamUser = $this->teams()->first();

        if ($teamUser) {
            return [
                'team' => $teamUser,
                'zone' => Zone::find($teamUser->pivot->zone_id)
            ];
        }
        return ['team' => null, 'zone' => null];
    }

    public function isModificationAdmin():bool
    {
        return $this->hasRole('modification_admin') || $this->email=='morsy.mamr@gmail.com';
    }

    // Check if user is team manager
    public function isTeamManager(Team $team): bool
    {
        return $this->hasRole($team->code . '_team_manager');
    }

    // Check if user is zone manager
    public function isZoneManager(Zone $zone): bool
    {

        $zoneCode = Zones::getCodeByValue($zone->code);
       
        return $this->hasRole($zoneCode . '_zone_manager');
    }

    // Check if user is site engineer
    public function isSiteEngineer(Team $team): bool
    {
        return $this->hasRole($team->code . '_site_engineer');
    }

    // Get user's zone in a specific team
    public function getZoneInArea(Team $team): ?Zone
    {
        $teamUser = $this->teams()->where('teams.id', $team->id)->first();
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
