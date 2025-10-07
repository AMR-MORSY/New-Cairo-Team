<?php

namespace App\Livewire\Users\Actions;

use App\Models\Area;
use App\Models\Team;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Collection;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    public  $roles ;

    public Collection $teams;


    public Collection $permissions;

  
      #[Validate]
    public int|null $team_id=null;
  
    public string $newRole;

    public array $newPermissions;


     protected function rules()
    {
        return [
            "team_id"=>'nullable|exists:teams,id',/////////////////when the team id is null, this means that the role is global and could be assigned to any user inside any team. All users must be belong to teams to have roles
            'newRole' => ['required',"min:3","max:40", Rule::unique('roles', 'name')->where(function ($query)  { return $query->where('team_id', $this->team_id);})],
            'newPermissions' => 'required|exists:permissions,name',
        ];
    }

    public function mount()
    {
        $roles = Role::all();
        $this->roles=$roles->map(function($role){

             $role->team=Team::find($role->team_id);
             return $role;
        });

   

        $this->teams = Team::all();

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
