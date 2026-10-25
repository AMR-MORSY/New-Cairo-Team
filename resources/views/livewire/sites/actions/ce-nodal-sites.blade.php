<div class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('CE Nodal Sites')" :subheading="__('You will find very important information about the Nodal sites in Cairo East')">
        <x-slot:links>
            <x-ui.navlist class="mt-20 lg:grid">
                <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">


                    <x-ui.navlist.item :href="route('site.CE.sites')" wire:navigate>
                        {{ __('BACK') }}
                    </x-ui.navlist.item>
                </x-ui.navlist.group>
            </x-ui.navlist>
        </x-slot:links>
        <div class="max-w-xl xl:max-w-3xl  2xl:max-w-6xl">

            {{-- <livewire:tables.site.nodal-sites-table :nodalSites="$sites" /> --}}

            <livewire:tables.site.nodal-sites-table target="nodals" />
        </div>
    </x-viewLayouts.main-view-layout>
</div>
