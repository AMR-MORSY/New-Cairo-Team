<div>

    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('Quotation Create')" :subheading="__('You will find very important information about the modification Quotation here. ')">


            {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}

            {{-- @if ($quotation) --}}

            {{-- <livewire:tables.modification.quotation-items-table :quotationItems="$quotationItems" target="details"/>  --}}

            {{-- <p class=" mt-6">Total Cost: {{$quotation->quotationCost()}}</p> --}}

            {{-- @else --}}
            <livewire:modifications.actions.submit-quotation :modification="$modification" />

            @if ($quotationItems)
                <p class=" mt-6 text-decoration-underline decoration-1"> Quotation Items:</p>
                <livewire:tables.modification.quotation-items-table :quotationItems="$quotationItems" target="" />
            @endif


            {{-- @endif --}}





        </x-viewLayouts.main-view-layout>
    </section>

</div>
