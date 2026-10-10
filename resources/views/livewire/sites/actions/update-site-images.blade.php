<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Update Site Images')" :subheading="__('You will find very important information about the power data of site ') .
        $site->site_code .
        '-' .
        $site->site_name">
        <x-slot:links>
            <livewire:sites.site-images-links :site="$site" />
        </x-slot:links>
        <div class=" mt-9">
            <!-- Preview Section -->
            @if ($site->getMedia('site-images')->count() > 0)
                <div class="flex gap-6 flex-wrap">
                    @foreach ($site->getMedia('site-images') as $media)
                        <div class=" flex flex-col items-center justify-between border  rounded p-2">
                            <div class=" w-24 h-24  rounded">
                                <img src="{{ $media->getUrl() }}" class= " w-full h-full object-cover rounded">

                            </div>

                            <div class=" m-2 flex items-center justify-center">
                                <button wire:click="deleteImage({{ $media->id }})"
                                    wire:confirm="Are you sure you want to delete this image?" 
                                    title="Delete image">
                                    <x-icons.trash class=" text-red-700 cursor-pointer" />

                                </button>
                            </div>


                        </div>
                    @endforeach
                </div>
            @endif
        </div>



    </x-viewLayouts.main-view-layout>
</section>
