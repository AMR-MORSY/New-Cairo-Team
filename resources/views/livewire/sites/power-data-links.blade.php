<x-ui.navlist class="mt-20 lg:grid">
    <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">





        @if ($page == 'powerData.update')
            {{-- <x-ui.navlist.item wire:click="delete" class=" cursor-pointer">
                {{ __('Delete') }}
            </x-ui.navlist.item> --}}
            <x-ui.navlist.item :href="route('site.powerData', $site->site_code)" wire:navigate>
                {{ __('Back') }}
            </x-ui.navlist.item>
        @elseif ($page == 'site.powerData')
            <x-ui.navlist.item :href="route('powerData.update', $powerData->id)" wire:navigate>
                {{ __('Update') }}
            </x-ui.navlist.item>
            <x-ui.navlist.item wire:click="mountAction('delete')" class=" cursor-pointer">
                {{ __('Delete') }}
            </x-ui.navlist.item>
            <x-ui.navlist.item :href="route('site.show', $site->site_code)" wire:navigate>
                {{ __('Back') }}
            </x-ui.navlist.item>
        @elseif ($page == 'site.noPowerData')
            <x-ui.navlist.item :href="route('site.show', $site->site_code)" wire:navigate>
                {{ __('Back') }}
            </x-ui.navlist.item>
        @elseif ($page == 'powerData.create')
            <x-ui.navlist.item :href="route('site.show', $site->site_code)" wire:navigate>
                {{ __('Back') }}
            </x-ui.navlist.item>
        @endif







    </x-ui.navlist.group>
    <x-filament-actions::modals />
</x-ui.navlist>
