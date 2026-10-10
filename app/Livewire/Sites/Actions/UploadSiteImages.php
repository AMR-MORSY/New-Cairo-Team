<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Storage;

class UploadSiteImages extends Component
{


    use WithFileUploads;

    public $photos = [];


    public $site;
    public function mount(Site $site)
    {
         $this->authorize('update', $site);
        $this->site = $site;
    }


    public function save()
    {
        $this->validate([
            "photos" => ['required', 'array', 'max:5'],
            'photos.*' => ['image', 'max:1024']
        ]);
        if ($this->photos) {
            $siteMediaPhotosCount = $this->site->getMedia('site-images')->count();
            $uploadedPhotosCount = count($this->photos);
            $remainingPhotosCount = 5 - $siteMediaPhotosCount;
            if ($remainingPhotosCount >= $uploadedPhotosCount) {
                foreach ($this->photos as $photo) {

                    $tempPath = $photo->store('site-images', 's3');


                    // Step 2: Copy from S3 to media library on S3
                    $this->site->addMediaFromDisk($tempPath, 's3')

                        ->toMediaCollection('site-images');

                    // Step 3: Clean up temp file
                    Storage::disk('s3')->delete($tempPath);
                }

                // Clear the photos after saving
                $this->photos = [];

                Toaster::success('Photos saved successfully');

                return redirect()->route('site.images', $this->site->site_code);
            }
            else{
                Toaster::error("Each site is eligible for 5 photos. You can upload up to $remainingPhotosCount photos  ")->duration(7000);
            }
          
        }
    }


    public function render()
    {
        return view('livewire.sites.actions.upload-site-images');
    }
}
