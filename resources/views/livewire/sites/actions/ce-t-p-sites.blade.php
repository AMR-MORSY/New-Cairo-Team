<div class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('CE TP Sites')" :subheading="__('You will find very important information about the TP sites in Cairo East')">

        <x-slot:links>
            <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
                <x-ui.navlist.item  :href="route('site.CE.sites')" wire:navigate>
                    {{ __('BACK') }}
                </x-ui.navlist.item>
            </x-ui.navlist.group>
        </x-slot:links>
        <div class="max-w-xl xl:max-w-3xl  2xl:max-w-6xl">
          
            {{-- <livewire:tables.site.nodal-sites-table :nodalSites="$sites" /> --}}
             <livewire:tables.site.nodal-sites-table target="TP" />
        </div>
    </x-viewLayouts.main-view-layout>
</div>
