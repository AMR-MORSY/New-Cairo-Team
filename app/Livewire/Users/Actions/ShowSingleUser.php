<?php

namespace App\Livewire\Users\Actions;

use App\Models\Area;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;
use Toaster;

class ShowSingleUser extends Component
{
    public object $user;
    public array $rolesAndPermissions;

    public $teamAndZone;

    public array $newRoles;

    public Collection  $teamRoles;

    public $newZone;

    #[Validate('required')]
    public $newTeam;

    public $teams;

    public int|null $team_id = null;

    public $zones;
    public function mount(User $user)
    {
        $this->teams = Area::all();

        $this->teamAndZone = $user->getAreaAndZone();
        // dd($this->teamAndZone);
        $team = $this->teamAndZone['area'];

        if ($team) {
            $this->team_id = $team->id; ////if the user does not belong to any team, only the generic roles could be assigned
        }

        $this->user = $user;



        // set active global team_id
        setPermissionsTeamId($this->team_id);
        $this->teamRoles = Role::where('team_id', $this->team_id)->orWhere('team_id', null)->get(); ///team and generic roles could be assigned as generic roles has null team_id

        // unset cached model relations so new team relations will get reloaded
        $user->unsetRelation('roles')->unsetRelation('permissions');
        $this->newRoles = $user->roles->pluck('name')->toArray();



        // dd($this->newRoles);
        $rolesAndPermissions = [];
        foreach ($this->newRoles as $role) {
            $rolePermission = [];
            $roleModel = Role::findByName($role);

            $rolePermission['role'] = $role;
            $rolePermission['permissions'] = $roleModel->permissions->pluck('name');
            array_push($rolesAndPermissions, $rolePermission);
        }


        $this->rolesAndPermissions = $rolesAndPermissions;
    }

    public function updateZoneAndTeam()
    {

        // dd($this->newZone);
        $this->validate();
        setPermissionsTeamId($this->team_id);

        /////revoking all user's roles before changing area and zone


        foreach ($this->newRoles as $role) {
            $this->user->removeRole($role);
        }

        if ($this->user->areas()->where('user_id', $this->user->id)->exists()) {

            //delete all user's areas with zones
            $this->user->areas()->newPivotQuery()
                ->where('user_id', $this->user->id)
                ->delete();

            // Create new relationship
            $this->user->areas()->attach($this->newTeam, [
                'zone_id' => $this->newZone,
                // 'area_id' => $this->newTeam

            ]);


            // // Update existing
            // $this->user->areas()->updateExistingPivot($this->newTeam, [
            //     'zone_id' => $this->newZone,
            //     // 'area_id' => $this->newTeam

            // ]);
        } else {
            // Create new relationship
            $this->user->areas()->attach($this->newTeam, [
                'zone_id' => $this->newZone,
                // 'area_id' => $this->newTeam

            ]);
        }
        return redirect()->route('user.show', ['user' => $this->user->id]);
    }
    public function updatedNewTeam($value)
    {
        $team = Area::find($value);
        $this->zones = $team->zones()->get();
    }

    public function updateRole()
    {



        setPermissionsTeamId($this->team_id);
        // dd($this->newRoles);
        $this->user->syncRoles($this->newRoles);

        Toaster::success('Roles updated Successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    public function render()
    {
        return view('livewire.users.actions.show-single-user', ["teamAndZone" => $this->teamAndZone, "user" => $this->user, 'teamRoles' => $this->teamRoles, 'teams' => $this->teams, 'zones' => $this->zones, "rolesAndPermissions" => $this->rolesAndPermissions]);
    }
}
