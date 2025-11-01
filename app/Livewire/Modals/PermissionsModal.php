<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\In;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Permission;

class PermissionsModal extends ModalComponent
{
   

    public $permissions;

    public $role;
    public function mount($role_name)
    {
         
         $this->role = Role::where('name',$role_name)->first();
     

        $this->permissions =Role::where('name',$role_name)->first()->permissions;
      
    }

     #[On('deleteRolePermission')]

    public function deleteRolePermission($permission_id) {
        $permission=Permission::find($permission_id);

        $this->role->revokePermissionTo($permission->name);

        return redirect()->route('role.index');

    }

    public function render()
    {
        return view('livewire.modals.permissions-modal',['permissions'=>$this->permissions]);
    }
}
