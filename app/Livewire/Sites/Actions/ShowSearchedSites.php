<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\Attributes\On;
use App\Models\Site\Cascade;
use Masmerise\Toaster\Toaster;

class ShowSearchedSites extends Component
{

    public $props;

    public $sites;

    public  $previousUrl;

    public function mount($target,$search,$updateSite=null)
    {
         $this->previousUrl = url()->previous();

        $this->sites = Site::where('site_code', 'like', "%$search%")->orWhere('site_name', 'like', "%$search%")->get();

        $this->props = [
            'title' => "Sites",
            'data' => $this->sites,
            'target' => $target,
            'updateSite'=>$updateSite

        ];



    }

     #[On('addSiteToCascades')]

    public function addSiteToCascades($site_code,$update_site_code)
    {
        
        if ($site_code == $update_site_code) {
           
            Toaster::error('Can not cascade on itself');
            return;
        } elseif (Cascade::where('cascade_code', $site_code)->exists()) {

            $nodal_code = Cascade::where('cascade_code', $site_code)->first()->nodal_code;
            $site = Site::where('site_code', $nodal_code)->first();
            
            Toaster::error("Already cascaded on $site->site_code-$site->site_name")->duration(7000);
            return;
        } else {

            $updateSite=Site::where('site_code',$update_site_code)->first();

            $updateSite->cascades()->create([
                'cascade_code' => $site_code,
                'nodal_code' =>$update_site_code,
                'cascade_name' => Site::where('site_code', $site_code)->first()->site_name


            ]);
            $cascadeSite= Site::where('site_code',$site_code)->first();
            Toaster::success("$cascadeSite->site_name.has been added successfully to $updateSite->site_name.cascades ")->duration(7000);
            return redirect()->route('site.cascades.update', ['site' =>$update_site_code]);
        }
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
