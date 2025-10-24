<div class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('CE Sites')" :subheading="__('You will find very important information about the batteries in site')">


        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East TP sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE TP sites with batteries.</flux:callout.text>
            <x-slot name="actions">
                <flux:button wire:click="CETPSites" class=" cursor-pointer">CE TP SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East Nodal sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE nodal sites with batteries.</flux:callout.text>
            <x-slot name="actions">
                <flux:button wire:click="CENodalSites" class=" cursor-pointer">CE NODAL SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East VIP sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE VIP sites with batteries.</flux:callout.text>
            <x-slot name="actions">
                <flux:button wire:click="CEVIPSites" class=" cursor-pointer">CE VIP SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East Golden Square sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE Golden Square sites with batteries.</flux:callout.text>
            <x-slot name="actions">
                <flux:button wire:click="CEGSquareSites" class=" cursor-pointer">CE G.SQUARE SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <div class=" max-w-full">
            {{-- <livewire:tables.site.ce-tp-sites :tpSites="$tpSites"/> --}}
            <livewire:tables.site.nodal-sites-table :nodalSites="$sites" />
        </div>
    </x-viewLayouts.main-view-layout>
</div>
