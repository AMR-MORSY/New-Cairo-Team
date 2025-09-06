<div>

    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('Quotation Update')" :subheading="__('You will find very important information about the modification Quotation here. ')">


            {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}


            {{-- <livewire:tables.modification.quotation-table :quotation_id="$quotation->id"/> --}}
            <livewire:tables.modification.quotation-items-table :quotationItems="$quotationItems" target="update" />

            <p class=" mt-6">Total Cost: {{ $quotation->quotationCost() }}</p>







        </x-viewLayouts.main-view-layout>
    </section>

</div>
