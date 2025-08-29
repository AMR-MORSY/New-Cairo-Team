<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Modification Details')" :subheading="__('You will find very important information about the modification action here. ')">

        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modifications="$modifications" />
        </x-slot:links>

        @if (count($modifications) > 0)
            {{-- <x-modification-create-form target="Details" :readonly="true" /> --}}
        @else
            <p class=" text-center text-2xl mt-11"> No Modifications Found On this site</p>
        @endif



    </x-viewLayouts.main-view-layout>
</section>
