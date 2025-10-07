<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Batteries Details')" :subheading="__('You will find very important information about site batteries. ')">


        <x-slot:links>
            <livewire:sites.batteries-links />
        </x-slot:links>
        @if (count($batteries) > 0)
            <livewire:tables.site.batteries-table />
        @else
            <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                <flux:callout.heading>No batteries Found in this site</flux:callout.heading>
                <flux:callout.text>click the button to go to new record creation page.</flux:callout.text>
                <x-slot name="actions">
                    <flux:button :href="route('battery.create',$site->site_code)" wire:navigate>New Battery Record
                    </flux:button>
                </x-slot>
            </flux:callout>
        @endif
    </x-viewLayouts.main-view-layout>
</section>
