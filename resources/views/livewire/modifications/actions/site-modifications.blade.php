<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Site Modifications')" :subheading="__('You will find very important information about the modification action here. ')">

        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modifications="$modifications" />
        </x-slot:links>


        @if (count($modifications) > 0)
            <div class=" mt-7">
                <livewire:tables.modification.site-modifications-table :modifications="$modifications" :site="$site" />
            </div>
        @else
            {{-- <p class=" text-center text-2xl mt-11"> No Modifications Found On this site</p> --}}
              <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <flux:callout.heading>No Modifications Found On this site</flux:callout.heading>
                    <flux:callout.text>click the button to go to modification creation page.</flux:callout.text>
                    <x-slot name="actions">
                        <flux:button :href="route('modification.create',$site->site_code)" wire:navigate>New Modification</flux:button>
                    </x-slot>
                </flux:callout>
        @endif



    </x-viewLayouts.main-view-layout>
</section>
