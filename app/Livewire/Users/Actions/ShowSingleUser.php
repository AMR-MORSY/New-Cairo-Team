<?php

namespace App\Livewire\Users\Actions;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class ShowSingleUser extends Component
{
    public object $user;
    public array $rolesAndPermissions;

    public function mount(User $user)
    {
        $teamAndZone = $user->getAreaAndZone();
        $team = $teamAndZone['area'];
        setPermissionsTeamId($team->id);
        $roles=$user->getRoleNames();
        $rolesAndPermissions=[];
        foreach($roles as $role)
        {
            $rolePermission=[];
            $roleModel = Role::findByName($role);
            
            $rolePermission['role']=$role;
            $rolePermission['permissions']=$roleModel->permissions->pluck('name');
            array_push($rolesAndPermissions,$rolePermission);
        }

        $this->user =$user;
        $this->rolesAndPermissions=$rolesAndPermissions;
    }
    public function render()
    {
        return view('livewire.users.actions.show-single-user');
    }
}
