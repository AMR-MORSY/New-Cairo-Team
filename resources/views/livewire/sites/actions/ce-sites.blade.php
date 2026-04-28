<div class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('CE Sites')" :subheading="__('You will find very important information about Cairo East sites')">


        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East TP sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE TP sites with batteries.</flux:callout.text>
            <x-slot name="actions">
              
                <flux:button :href="route('site.CE.sites.TP')" class=" cursor-pointer" wire:navigate>CE TP SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East Nodal sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE nodal sites with batteries.</flux:callout.text>
            <x-slot name="actions">
         
                <flux:button :href="route('site.CE.sites.nodal')" class=" cursor-pointer" wire:navigate>CE NODAL SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East VIP sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE VIP sites with batteries.</flux:callout.text>
            <x-slot name="actions">

                <flux:button :href="route('site.CE.sites.vip')" class=" cursor-pointer" wire:navigate>CE VIP
                    SITES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East Golden Square sites</flux:callout.heading>
            <flux:callout.text>click the button to view CE Golden Square sites with batteries.</flux:callout.text>
            <x-slot name="actions">

                <flux:button :href="route('site.CE.sites.golden')" class=" cursor-pointer" wire:navigate>CE G.SQUARE
                    SITES
                </flux:button>
            </x-slot>
        </flux:callout>
          <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>Cairo East Batteries data</flux:callout.heading>
            <flux:callout.text>click the button to view CE batteries data.</flux:callout.text>
            <x-slot name="actions">

                <flux:button :href="route('site.CE.sites.batteries')" class=" cursor-pointer" wire:navigate>CE BATTERIES
                </flux:button>
            </x-slot>
        </flux:callout>

    </x-viewLayouts.main-view-layout>
</div>
