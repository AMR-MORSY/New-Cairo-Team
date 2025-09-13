<div>
    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('Create PO')" :subheading="__('You will find very important information about the POs here. ')">


            {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}
            <x-po-creation-form target="New" :readonly="false" :subcontractors="$subcontractors" />






        </x-viewLayouts.main-view-layout>
    </section>

</div>
