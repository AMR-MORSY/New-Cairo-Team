<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Site\Site;
use Masmerise\Toaster\Toaster;

#[Title('Update Site Images')]
class UpdateSiteImages extends Component
{
    public Site $site;
    public function mount(Site $site)
    {
         $this->authorize('update', $site);
        $this->site = $site;
    }

    public function deleteImage($mediaId)
    {
        // the page check ran at mount; the role may have gone since
        $this->authorize('update', $this->site);

        // through the site, so an id from another site's images finds nothing
        $media = $this->site->media()->whereKey($mediaId)->first();

        if (! $media) {
            Toaster::error('That image is no longer on this site.');

            return redirect()->route('site.images', $this->site->site_code);
        }

        $media->delete();

        return redirect()->route('site.images',$this->site->site_code);
    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-images');
    }
}
