{{-- Same shape as the dashboard sidebar in components/layouts/app/sidebar.blade.php:
     one <x-ui.navlist> owning the group, not a group wrapped around a second
     list - navlist.group renders an <li>, so it needs the <ul> above it. --}}
<x-ui.navlist class="lg:grid">
    <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">

        @if (request()->routeIs('site.show'))
            {{-- <x-ui.navlist.item icon="eye" :href="route('site.show', $site->site_code)" wire:navigate>
                {{ __('Site Details') }}
            </x-ui.navlist.item> --}}

            <x-ui.navlist.item icon="pencil-square" :href="route('site.update', $site->site_code)" wire:navigate>
                {{ __('Update') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="folder-git-2" :href="route('site.modifications', $site->site_code)" :current="request()->routeIs('site.modifications')" wire:navigate>
                {{ __('Modifications') }}
            </x-ui.navlist.item>

            {{-- <x-ui.navlist.item icon="clipboard" href="#" wire:navigate>
                {{ __('NUR') }}
            </x-ui.navlist.item> --}}

            <x-ui.navlist.item icon="battery-50" :href="route('site.batteries', $site->site_code)" wire:navigate>
                {{ __('Batteries Data') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="book-open-text" :href="route('site.siteData', $site->site_code)" wire:navigate>
                {{ __('Site Data') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="bolt" :href="route('site.powerData', $site->site_code)" wire:navigate>
                {{ __('Power Data') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="document-text" :href="route('site.notes', $site->site_code)" wire:navigate>
                {{ __('Notes') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="photo" :href="route('site.images', $site->site_code)" wire:navigate>
                {{ __('Images') }}
            </x-ui.navlist.item>
        @elseif (request()->routeIs('site.update'))
            <x-ui.navlist.item icon="arrow-left" :href="route('site.show', $site->site_code)" wire:navigate class="cursor-pointer">
                {{ __('Back') }}
            </x-ui.navlist.item>
        @endif

    </x-ui.navlist.group>
</x-ui.navlist>
