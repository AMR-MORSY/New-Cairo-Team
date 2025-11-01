<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;

class ShowSearchedSites extends Component
{

    public $props;

    public $sites;

    public  $previousUrl;

    public function mount($search)
    {
         $this->previousUrl = url()->previous();

        $this->sites = Site::where('site_code', 'like', "%$search%")->orWhere('site_name', 'like', "%$search%")->get();

        $this->props = [
            'title' => "Sites",
            'data' => $this->sites,
            'target' => "showSites"

        ];



        // if (count($sites) > 0) {

        //     $props = [
        //         'title' => "Sites",
        //         'data' => $sites,
        //         'target'=>$this->target

        //     ];

        //     // $this->dispatch('openDynamicModal', 'tables.site.searched-sites-table', $props);
        //     $this->dispatch('openModal', component:'modals.search-result-sites',  arguments: ['props' => $props]);
        // }
        // else{
        //       Toaster::info('No data found');

        // }

    }

    public function goBack()
    {
        
        return redirect()->to($this->previousUrl);
    }
    public function render()
    {
        return view('livewire.sites.actions.show-searched-sites');
    }
}
