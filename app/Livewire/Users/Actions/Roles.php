<?php

namespace App\Livewire\Users\Actions;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Collection;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    public  $roles;
    public  $previousUrl;


    public Collection $permissions;

    public string $newRole;

    public array $newPermissions;


    protected function rules()
    {
        return [
            'newRole' => ['required', "min:3", "max:40", Rule::unique('roles', 'name')],
            'newPermissions' => 'required|exists:permissions,name',
        ];
    }

    public function mount()
    {
        $this->previousUrl = url()->previous();
        $roles = Role::all();
        $this->roles = $roles;

        $this->permissions = Permission::all();
    }

    public function createRole()
    {
        $this->validate();

        $role = Role::create([
            "name" => $this->newRole,
            "guard_name" => 'web',
        ]);
        if ($role) {
            $role->givePermissionTo($this->newPermissions);
        }

        return redirect()->route('role.index');
    }

    public function goBack()
    {

        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.users.actions.roles', ['roles' => $this->roles]);
    }
}
