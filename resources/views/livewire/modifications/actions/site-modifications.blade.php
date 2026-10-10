<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Site Modifications')" :subheading="__('You will find very important information about the modification action here. ')">

        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" page="modifications" />
        </x-slot:links>


        @if (count($modifications) > 0)
            <div class=" max-w-xl xl:max-w-3xl  2xl:max-w-6xl mt-7">
                <livewire:tables.modification.site-modifications-table :modifications="$modifications" :site="$site" />
            </div>
        @else
            {{-- <p class=" text-center text-2xl mt-11"> No Modifications Found On this site</p> --}}
              <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>No Modifications Found On this site</x-ui.callout.heading>
                    <x-ui.callout.text>click the button to go to modification creation page.</x-ui.callout.text>
                    <x-slot name="actions">
                        <x-ui.button :href="route('modification.create',$site->site_code)" wire:navigate>New Modification</x-ui.button>
                    </x-slot>
                </x-ui.callout>
        @endif



    </x-viewLayouts.main-view-layout>
</section>
