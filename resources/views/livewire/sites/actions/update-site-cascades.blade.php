<div>
    <div class=" w-full flex items-center justify-center">
        <flux:callout.heading>{{ $site->site_code }}-{{ $site->site_name }} Cascades</flux:callout.heading>
    </div>

    <livewire:tables.site.cascades-table :cascades=$cascades cascadesType='direct' />

    <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
        <flux:callout.heading :href="route('site.show',['site'=>$site->site_code])" wire:navigate class=" cursor-pointer">{{ $site->site_code }}-{{ $site->site_name }}</flux:callout.heading>
        <flux:callout.text>Search for sites to add them into the above cascaded sites list.</flux:callout.text>
        <x-slot name="actions">
            <livewire:sites.searching-for-site target='site.cascades.update' />
            {{-- <flux:button :href="route('site.cascades.update',['site'=>$site->site_code])">UPDATE CASCADES
                </flux:button> --}}
        </x-slot>
    </flux:callout>
</div>
