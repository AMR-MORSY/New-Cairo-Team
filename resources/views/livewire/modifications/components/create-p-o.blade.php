<div>
    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('New PO')" :subheading="__('You can create New POs here . ')">


            {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}



            <x-po-creation-form :subcontractors="$subcontractors" target="New" :readonly="false" />

           


        </x-viewLayouts.main-view-layout>
    </section>

</div>
