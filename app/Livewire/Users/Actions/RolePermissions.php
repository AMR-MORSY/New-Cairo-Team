<?php

namespace App\Livewire\Users\Actions;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;

class RolePermissions extends Component
{
    public $permissions;
    public $role;
    public function mount(Role $role)
    {
        $this->permissions=$role->permissions;
        $this->role=$role;
     

    }

    #[On ('deleteRolePermission')]
    
    public function deleteRolePermission($permission_name)
    {
       $this->role->revokePermissionTo($permission_name);
       return redirect()->route('role.permissions',$this->role->id);

    }
    public function render()
    {
        return view('livewire.users.actions.role-permissions');
    }
}
