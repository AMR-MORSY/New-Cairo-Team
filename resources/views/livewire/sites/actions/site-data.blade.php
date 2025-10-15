<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Site Data Details')" :subheading="__('You will find very important information about the power data of site ') .
        $site->site_code .
        '-' .
        $site->site_name">



        @if ($siteData)
            <x-slot:links>
                <livewire:sites.site-data-links :site="$site" :siteData="$siteData" />
            </x-slot:links>
            <x-site-data-creation-form :target="null" :readonly="true" :site="$site" />
        @else
            <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                <flux:callout.heading>No site Data Found for this site</flux:callout.heading>
                <flux:callout.text>click the button to go to new record creation page.</flux:callout.text>
                <x-slot name="actions">
                    <flux:button :href="route('siteData.create',$site->site_code)" wire:navigate>New Site data Record
                    </flux:button>
                </x-slot>
            </flux:callout>
        @endif
    </x-viewLayouts.main-view-layout>
</section>
