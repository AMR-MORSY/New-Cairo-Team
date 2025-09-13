<div>
    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('View POs')" :subheading="__('You can customize the search for subcontractor POs by adding PO NO. or PO type or PO status . ')">


            {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}



            <x-po-search-form :subcontractors="$subcontractors" />

          
                <livewire:tables.modification.p-os-table />
         



        </x-viewLayouts.main-view-layout>
    </section>

</div>
