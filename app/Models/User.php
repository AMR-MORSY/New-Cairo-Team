<?php

namespace App\Models;

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Modification\Modification;
use App\Notifications\StaffInvitation;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;

    /**
     * Filament lets any authenticated user into a panel unless told
     * otherwise, which would put the lookup-table editors in reach of every
     * engineer. Gate it on the same ability the users/roles screens use, so
     * there is one answer to "who administers this application".
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can('handle_users_activities');
    }

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
        'team',
        'zone',
    ];

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
            'login_at' => 'datetime:Y-m-d H:i:s',
            'team' => Teams::class,
            'zone' => Zones::class,
        ];
    }

    public function modifications(): HasMany
    {
        return $this->hasMany(Modification::class, 'action_owner');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    |
    | A user's reach is their team / zone columns. Super admins clear every
    | check through the Gate::before in AppServiceProvider, not through here.
    |
    */

    public function inTeam(?Teams $team): bool
    {
        return $team !== null && $this->team === $team;
    }

    public function inZone(?Zones $zone): bool
    {
        return $zone !== null && $this->zone === $zone;
    }

    /*
    |--------------------------------------------------------------------------
    | Role checks
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRoles::SUPER_ADMIN->value);
    }

    // Modification admin - authority over modifications in every team
    public function isModificationAdmin(): bool
    {
        return $this->hasRole(UserRoles::MODIFICATION_ADMIN->value) || $this->isSuperAdmin();
    }

    // Site admin - authority over sites in every team
    public function isSiteAdmin(): bool
    {
        return $this->hasRole(UserRoles::SITE_ADMIN->value) || $this->isSuperAdmin();
    }

    // Team manager of the given team
    public function isTeamManager(?Teams $team): bool
    {
        return $this->inTeam($team) && $this->hasRole(UserRoles::TEAM_MANAGER->value);
    }

    // Zone manager of the given zone
    public function isZoneManager(?Zones $zone): bool
    {
        return $this->inZone($zone) && $this->hasRole(UserRoles::ZONE_MANAGER->value);
    }

    // Site engineer in the given team 
    public function isSiteEngineer(?Teams $team): bool
    {
        return $this->inTeam($team) && $this->hasRole(UserRoles::SITE_ENGINEER->value);
    }

    // Maintenance engineer in the given zone
    public function isMaintenanceEngineer(?Zones $zone): bool
    {
        return $this->inZone($zone) && $this->hasRole(UserRoles::MAINTENANCE_ENGINEER->value);
    }

    /*
    |--------------------------------------------------------------------------
    | Queries
    |--------------------------------------------------------------------------
    */

    public function scopeInTeamScope($query, Teams $team)
    {
        return $query->where('team', $team->value);
    }

    public function scopeInZoneScope($query, Zones $zone)
    {
        return $query->where('zone', $zone->value);
    }

    public function scopeWithRole($query, UserRoles $role)
    {
        return $query->whereHas('roles', fn($q) => $q->where('name', $role->value));
    }

    /**
     * Email this user a link to choose their own password.
     *
     * Used when an admin creates the account and when they resend the invitation.
     * The callback only swaps in the invitation wording; the link itself comes
     * from the guard-scoped `password.reset` route via ResetPassword::createUrlUsing(),
     * so it stays valid no matter which panel the recipient can reach.
     *
     * @return string Password::RESET_LINK_SENT | INVALID_USER | RESET_THROTTLED
     */
    public function sendStaffInvitation(): string
    {
        return Password::broker('users')->sendResetLink(
            ['email' => $this->email],
            // the broker calls this as ($user, $token) - taking only one
            // parameter silently binds the User model to $token
            function (User $user, string $token): void {
                $user->notify(app(StaffInvitation::class, ['token' => $token]));
            },
        );
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
