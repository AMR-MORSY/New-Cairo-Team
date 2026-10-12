
    <x-viewLayouts.main-view-layout :heading="__('Batteries Details')" :subheading="__('You will find very important information about the batteries in site ').$site->site_code.'-'.$site->site_name">


        <x-slot:links>
            <livewire:sites.batteries-links :site="$site" :page="$page" />
        </x-slot:links>
        @if (count($batteries) > 0)
        <div class="max-w-xl xl:max-w-3xl  2xl:max-w-7xl">

            <livewire:tables.site.batteries-table :site="$site" />
        </div>
            
        @else
            <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                <x-ui.callout.heading>No batteries Found in this site</x-ui.callout.heading>
                <x-ui.callout.text>click the button to go to new record creation page.</x-ui.callout.text>
                <x-slot name="actions">
                    <x-ui.button :href="route('battery.create',$site->site_code)" wire:navigate>New Battery Record
                    </x-ui.button>
                </x-slot>
            </x-ui.callout>
        @endif
    </x-viewLayouts.main-view-layout>

