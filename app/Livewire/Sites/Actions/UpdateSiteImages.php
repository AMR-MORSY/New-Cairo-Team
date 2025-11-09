<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateSiteImages extends Component
{
    public $site;
    public function mount(Site $site)
    {
        $this->site = $site;
    }

    public function deleteImage($mediaId)
    {
       
        $media = Media::find($mediaId);
        $media->delete();

        return redirect()->route('site.images',$this->site->site_code);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-images');
    }
}
