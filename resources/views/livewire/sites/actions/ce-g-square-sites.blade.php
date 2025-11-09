<x-viewLayouts.main-view-layout :heading="__('CE Golden Square Sites')" :subheading="__('You will find very important information about the Golden Square sites in Cairo East')">

    <x-slot:links>
        <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
            <flux:navlist.item :href="route('site.CE.sites')" wire:navigate>
                {{ __('BACK') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </x-slot:links>
    <div class="max-w-xl xl:max-w-3xl  2xl:max-w-6xl">

        {{-- <livewire:tables.site.nodal-sites-table :nodalSites="$sites" /> --}}
         <livewire:tables.site.nodal-sites-table target="GSquare" />
    </div>
</x-viewLayouts.main-view-layout>
