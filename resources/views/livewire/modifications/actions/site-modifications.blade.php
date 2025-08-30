<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Site Modification')" :subheading="__('You will find very important information about the modification action here. ')">

        {{-- <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modifications="$modifications" />
        </x-slot:links> --}}

        @if (count($modifications) > 0)
            <livewire:tables.modification.site-modifications-table :modifications="$modifications"/>
        @else
            <p class=" text-center text-2xl mt-11"> No Modifications Found On this site</p>
        @endif



    </x-viewLayouts.main-view-layout>
</section>
