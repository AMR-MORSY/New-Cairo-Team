<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use WireUi\Traits\WireUiActions;

class ShowModification extends Component
{
    use WireUiActions;

    public $modifications;
    public $site;
    public function mount(Site $site)
    {

        $this->modifications = $site->modifications()->get();
        $this->site=$site;

        // dd($this->modifications);
        // $this->infoDialog();
    }
    // public function infoDialog(): void
    // {
    //     $this->dialog()->show([
    //         'icon' => 'info',
    //         'title' => 'Info Dialog!',
    //         'description' => 'This is a description.',
    //         'accept'      => [
    //             'label'  => 'ok', // Custom button text
    //             'color'  => 'zinc',      // Button color        // Button size
    //             'icon'   => 'check',        // Button icon
    //             'class'   => 'w-full',
    //         ]
    //     ]);
    // }

    // public function checkModifications()
    // {
    //     $count = count($this->modifications);


    //     if ($count <= 0) {

    //         $this->dialog()->show([
    //             'icon' => 'info',
    //             'title' => 'Info Dialog!',
    //             'description' => 'This is a description.',
    //         ]);
    //          return redirect()->back();
    //     }
    // }
    public function render()
    {
        return view('livewire.modifications.actions.show-modification',['site'=>$this->site,'modifications'=>$this->modifications]);
    }
}
