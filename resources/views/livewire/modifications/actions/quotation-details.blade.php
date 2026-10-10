<div>
    {{-- @if ($quotation->is_active==0)
    
        
    @else
        
    @endif --}}

    <section class="w-full">
        <x-viewLayouts.main-view-layout :heading="__('Quotation Details')" :subheading="$subHeading">


            <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" :site="$site" />
                </x-slot:links>

            @if ($quotation)
                <livewire:tables.modification.quotation-items-table :quotationItems="$quotationItems" target="details" />

                <p class=" mt-6">Total Cost: {{ $quotation->quotationCost() }}</p>
            @else
                <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>No quotation found</x-ui.callout.heading>
                    <x-ui.callout.text>click the button to go to quotation creation page.</x-ui.callout.text>
                    <x-slot name="actions">
                        <x-ui.button :href="route('quotation.create',['modification'=>$modification->id])" wire:navigate>Quotation Creation</x-ui.button>
                    </x-slot>
                </x-ui.callout>
                {{-- <livewire:modifications.actions.submit-quotation :modification="$modification" /> --}}
            @endif





        </x-viewLayouts.main-view-layout>
    </section>

</div>
