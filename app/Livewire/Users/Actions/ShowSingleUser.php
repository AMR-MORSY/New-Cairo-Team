<?php

namespace App\Livewire\Users\Actions;

use App\Enums\Teams;
use App\Enums\Zones;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Toaster;

/**
 * A user's scope is two columns now - users.team and users.zone - so there is
 * no pivot to attach and no roles to revoke when the scope changes. Roles say
 * what someone may do; the columns say where.
 */
class ShowSingleUser extends Component
{

    public User $user;

    public array $rolesAndPermissions = [];

    public array $newRoles = [];

    public Collection $availableRoles;

    /** Option lists for the selects. */
    public array $teams = [];

    public array $zones = [];

    public ?string $newTeam = null;

    public ?string $newZone = null;

    public $previousUrl;

    public function mount(User $user)
    {
        $this->previousUrl = url()->previous();
        $this->user = $user;

        $this->newTeam = $user->team?->value;
        $this->newZone = $user->zone?->value;

        $this->teams = $this->teamOptions();
        $this->zones = $this->zoneOptions($user->team);

        $this->availableRoles = Role::orderBy('name')->get();
        $this->newRoles = $user->roles->pluck('name')->toArray();

        $this->loadRolesAndPermissions();
    }

    private function teamOptions(): array
    {
        return array_map(
            fn (Teams $team) => ['value' => $team->value, 'label' => $team->label()],
            Teams::cases()
        );
    }

    private function zoneOptions(?Teams $team): array
    {
        $cases = $team ? Zones::forTeam($team) : Zones::cases();

        return array_map(
            fn (Zones $zone) => ['value' => $zone->value, 'label' => $zone->label()],
            $cases
        );
    }

    private function loadRolesAndPermissions(): void
    {
        $this->rolesAndPermissions = [];

        foreach ($this->user->roles as $role) {
            $this->rolesAndPermissions[] = [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ];
        }
    }

    /** Narrow the zone list when a team is picked. */
    public function updatedNewTeam($value): void
    {
        $team = $value ? Teams::tryFrom($value) : null;

        $this->zones = $this->zoneOptions($team);
        $this->newZone = null;
    }

    public function updateZoneAndTeam()
    {
        $this->validate([
            'newTeam' => ['required', new Enum(Teams::class)],
            'newZone' => ['nullable', new Enum(Zones::class)],
        ]);

        $team = Teams::from($this->newTeam);
        $zone = $this->newZone ? Zones::from($this->newZone) : null;

        if ($zone && $zone->team() !== $team) {
            $this->addError('newZone', 'The selected zone does not belong to this team.');

            return null;
        }

        $this->user->update(['team' => $team, 'zone' => $zone]);

        Toaster::success('Team and zone updated successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    public function updateRole()
    {
        $this->user->syncRoles($this->newRoles);

        Toaster::success('Roles updated Successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    /** Clear the user's scope; their roles are left alone. */
    public function delete()
    {
        $this->user->update(['team' => null, 'zone' => null]);

        Toaster::success('Team removed successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    public function goBack()
    {
        return redirect()->to($this->previousUrl);
    }

    public function render()
    {
        return view('livewire.users.actions.show-single-user', [
            'user' => $this->user,
            'teamRoles' => $this->availableRoles,
            'teams' => $this->teams,
            'zones' => $this->zones,
            'rolesAndPermissions' => $this->rolesAndPermissions,
        ]);
    }
}
