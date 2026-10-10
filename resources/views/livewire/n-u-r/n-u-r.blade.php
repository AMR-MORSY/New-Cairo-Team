<x-viewLayouts.main-view-layout :heading="__('NUR')" :subheading="__('You will find very important information about the NUR')">

    <x-slot:links>
        <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
            <x-ui.navlist.item wire:click="goBack">
                {{ __('BACK') }}
            </x-ui.navlist.item>
        </x-ui.navlist.group>
    </x-slot:links>
    <div class="max-w-xl xl:max-w-3xl  2xl:max-w-6xl">
        <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <x-ui.callout.heading>Cairo East TP sites</x-ui.callout.heading>
            <x-ui.callout.text>click the button to view CE TP sites with batteries.</x-ui.callout.text>
            <x-slot name="actions">

                <x-ui.button :href="route('site.CE.sites.TP')" class=" cursor-pointer" wire:navigate>CE TP SITES
                </x-ui.button>
            </x-slot>
        </x-ui.callout>
        <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <x-ui.callout.heading>Cairo East Nodal sites</x-ui.callout.heading>
            <x-ui.callout.text>click the button to view CE nodal sites with batteries.</x-ui.callout.text>
            <x-slot name="actions">

                <x-ui.button :href="route('site.CE.sites.nodal')" class=" cursor-pointer" wire:navigate>CE NODAL SITES
                </x-ui.button>
            </x-slot>
        </x-ui.callout>
        <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <x-ui.callout.heading>Cairo East VIP sites</x-ui.callout.heading>
            <x-ui.callout.text>click the button to view CE VIP sites with batteries.</x-ui.callout.text>
            <x-slot name="actions">

                <x-ui.button :href="route('site.CE.sites.vip')" class=" cursor-pointer" wire:navigate>CE VIP
                    SITES
                </x-ui.button>
            </x-slot>
        </x-ui.callout>
        <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <x-ui.callout.heading>Cairo East Golden Square sites</x-ui.callout.heading>
            <x-ui.callout.text>click the button to view CE Golden Square sites with batteries.</x-ui.callout.text>
            <x-slot name="actions">

                <x-ui.button :href="route('site.CE.sites.golden')" class=" cursor-pointer" wire:navigate>CE G.SQUARE
                    SITES
                </x-ui.button>
            </x-slot>
        </x-ui.callout>
        <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <x-ui.callout.heading>Cairo East Batteries data</x-ui.callout.heading>
            <x-ui.callout.text>click the button to view CE batteries data.</x-ui.callout.text>
            <x-slot name="actions">

                <x-ui.button :href="route('site.CE.sites.batteries')" class=" cursor-pointer" wire:navigate>CE BATTERIES
                </x-ui.button>
            </x-slot>
        </x-ui.callout>




    </div>
</x-viewLayouts.main-view-layout>
