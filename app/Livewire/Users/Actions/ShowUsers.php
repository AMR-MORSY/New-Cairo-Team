<?php

namespace App\Livewire\Users\Actions;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShowUsers extends Component
{

    public Collection $users;


    public function mount()
    {
        $this->users=User::with(['areas'])->get();

        //  dd($this->users);
    }


    public function render()
    {
        return view('livewire.users.actions.show-users',['users'=>$this->users]);
    }
}
