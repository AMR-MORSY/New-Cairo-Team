<?php

namespace App\Livewire\Users\Actions;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShowUsers extends Component
{

    public Collection $users;

    public  $previousUrl;
    public function mount()
    {
        $this->previousUrl = url()->previous();
        $this->users = User::all();   // team is a column now, not a relation

        //  dd($this->users);
    }

    public function goBack()
    {

        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.users.actions.show-users', ['users' => $this->users]);
    }
}
