<x-viewLayouts.main-view-layout :heading="__('Site Images')" :subheading="__('You will find very important information about the power data of site ') .
    $site->site_code .
    '-' .
    $site->site_name">
    <x-slot:links>
        <livewire:sites.site-images-links :site="$site" />
    </x-slot:links>
    <div>


        <div>

            @if ($site->getMedia('site-images')->count() > 0)
                <!-- Display existing images from S3 -->

                <div class="mt-6 flex items-center justify-between ">

                    <div class="carousel w-3/4 xl:w-2/4 mx-auto h-96">

                        @foreach ($site->getMedia('site-images') as $index => $media)
                            <div id="slide{{ $index + 1 }}" class="carousel-item relative w-full  ">
                                <img src="{{ $media->getUrl('carousel') }}" class="w-full rounded-2xl" alt="{{ $media->name }}" />
                                <div
                                    class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                    <a href="#slide{{ $index == 0 ? count($site->getMedia('site-images')) : $index }}"
                                        class="btn btn-circle">❮</a>
                                    <a href="#slide{{ $index + 2 > count($site->getMedia('site-images')) ? 1 : $index + 2 }}"
                                        class="btn btn-circle">❯</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>{{ $site->site_code }}-{{ $site->site_name }}</x-ui.callout.heading>
                    <x-ui.callout.text>There are no images to show.click the button to go to upload page.
                    </x-ui.callout.text>
                    <x-slot name="actions">
                        <x-ui.button :href="route('site.images.upload',$site->site_code)" wire:navigate>UPLOAD
                        </x-ui.button>
                    </x-slot>
                </x-ui.callout>
            @endif
        </div>
    </div>
</x-viewLayouts.main-view-layout>
