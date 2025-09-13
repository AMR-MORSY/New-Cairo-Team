<?php

namespace App\Livewire\Users\Actions;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;





class Permissions extends Component
{
    public object|null $permissions=null;

   
   #[Validate('required|min:3|max:40|unique:permissions,name')]
    public $permit="";


    public function mount()
    {
        $this->permissions=Permission::all();
        
    }

    public function createPermission()
    {
        $this->validate();

        $permission=Permission::create(["name"=>$this->permit]);

        return redirect()->route("permission.index");
    }

    public function render()
    {
        return view('livewire.users.actions.permissions',['permissions'=>$this->permissions]);
    }
}
