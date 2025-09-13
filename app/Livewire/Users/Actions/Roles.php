<?php

namespace App\Livewire\Users\Actions;

use App\Models\Area;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Validation\Rule;

class Roles extends Component
{
    public object|null $roles = null;

    public Collection $areas;


    public Collection $permissions;

  
      #[Validate]
    public int|null $team_id=null;
  
    public string $newRole;

    public array $newPermissions;


     protected function rules()
    {
        return [
            "team_id"=>'nullable|exists:areas,id',
            'newRole' => ['required',"min:3","max:40", Rule::unique('roles', 'name')->where(function ($query)  { return $query->where('team_id', $this->team_id);})],
            'newPermissions' => 'required|exists:permissions,name',
        ];
    }

    public function mount()
    {
        $this->roles = Role::with('teams')->get();

       

        $this->areas = Area::all();

        $this->permissions = Permission::all();
    }

    public function createRole()
    {
        $this->validate();

       $role=Role::create([
        "name"=>$this->newRole,
        "team_id"=>$this->team_id
       ]);
       if($role)
       {
        $role->givePermissionTo($this->newPermissions);
       }

       return redirect()->route('role.index');
    }
    public function render()
    {
        return view('livewire.users.actions.roles', ['roles' => $this->roles]);
    }
}
