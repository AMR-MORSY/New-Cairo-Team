<?php

namespace App\Livewire\Users\Actions;

use Toaster;
use App\Models\Area;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Livewire\Attributes\Validate;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class ShowSingleUser extends Component
{
    use WireUiActions;
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
        $this->teams = Team::all();

        $this->teamAndZone = $user->getTeamAndZone();

        $team = $this->teamAndZone['team'];

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

        if ($this->user->teams()->where('user_id', $this->user->id)->exists()) {

            //delete all user's areas with zones
            $this->user->teams()->newPivotQuery()
                ->where('user_id', $this->user->id)
                ->delete();

            // Create new relationship
            $this->user->teams()->attach($this->newTeam, [
                'zone_id' => $this->newZone,


            ]);
        } else {
            // Create new relationship
            $this->user->teams()->attach($this->newTeam, [
                'zone_id' => $this->newZone,


            ]);
        }
        return redirect()->route('user.show', ['user' => $this->user->id]);
    }
    public function updatedNewTeam($value)
    {
        $team = Team::find($value);
        $this->zones = $team->zones()->get();
    }

    public function updateRole()
    {


    //   dd($this->newRoles);

        setPermissionsTeamId($this->team_id);
        // dd($this->newRoles);
        $this->user->syncRoles($this->newRoles);

        Toaster::success('Roles updated Successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    public function delete()
    {
        $this->user->teams()->newPivotQuery()
            ->where('user_id', $this->user->id)
            ->delete();
        Toaster::success('Team deleted Successfully');

        return redirect()->route('user.show', ['user' => $this->user->id]);
    }

    public function deleteTeam()
    {

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Delete this Team?',
            'acceptLabel' => 'Yes, delete it',
            'method' => 'delete',
            'params' => 'Saved',
        ]);
    }

    public function render()
    {
        return view('livewire.users.actions.show-single-user', ["teamAndZone" => $this->teamAndZone, "user" => $this->user, 'teamRoles' => $this->teamRoles, 'teams' => $this->teams, 'zones' => $this->zones, "rolesAndPermissions" => $this->rolesAndPermissions]);
    }
}
