<?php

namespace App\Livewire\NUR;

use Livewire\Component;

class NUR extends Component
{
    public $weeks = [], $years = [];
    public  $previousUrl;

    public function mount()
    {
        $this->previousUrl = url()->previous();
        for ($i = 1; $i <= 52; $i++) {
            array_push($this->weeks, $i);
        }
        for ($i = 2022; $i <= 2050; $i++) {
            array_push($this->years, $i);
        }
    }

    public function goBack()
    {

        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.n-u-r.n-u-r');
    }
}
