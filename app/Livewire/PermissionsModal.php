<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\In;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Permission;

class PermissionsModal extends ModalComponent
{
    public int $role_id;

    public $permissions;

    public $role;
    public function mount()
    {
         $this->role = Role::find($this->role_id);

        $this->permissions = $this->role->permissions;
    }

     #[On('deleteRolePermission')]

    public function deleteRolePermission($permission_id) {
        $permission=Permission::find($permission_id);

        $this->role->revokePermissionTo($permission->name);

        return redirect()->route('role.index');

    }

    public function render()
    {
        return view('livewire.permissions-modal',['permissions'=>$this->permissions]);
    }
}
