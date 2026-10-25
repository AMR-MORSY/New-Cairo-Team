@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    {{-- Filament Actions outside the /admin panel: the modal markup that
         <x-filament-actions::modals /> renders needs these two. --}}
    @filamentStyles
</head>

<body class="min-h-svh bg-base-200 text-base-content antialiased" x-data="{ sidebar: false }">

    {{-- backdrop for the off-canvas sidebar --}}
    <div x-show="sidebar" x-cloak x-transition.opacity x-on:click="sidebar = false"
        class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

    <div class="flex min-h-svh">

        <aside x-bind:class="sidebar ? 'translate-x-0' : '-translate-x-full'"
            x-on:keydown.escape.window="sidebar = false"
            class="fixed inset-y-0 start-0 z-50 flex w-72 flex-col gap-4 overflow-y-auto border-e border-base-300 bg-base-100 p-4 transition-transform duration-200 lg:sticky lg:top-0 lg:h-svh lg:translate-x-0">

            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2" wire:navigate>
                    <x-app-logo />
                </a>

                <button type="button" class="btn btn-ghost btn-square btn-sm lg:hidden"
                    x-on:click="sidebar = false" aria-label="{{ __('Close menu') }}">
                    <x-icons.x-mark class="size-5" />
                </button>
            </div>

            <livewire:Sites.SearchingForSite target="searchForSite" :updateSite="null" />

            <x-ui.navlist>
                <x-ui.navlist.group :heading="__('Platform')">
                    <x-ui.navlist.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-ui.navlist.item>
                </x-ui.navlist.group>

        
                <x-ui.navlist.group expandable :heading="__('Sites')"
                    :expanded="request()->routeIs('site.*')">
                    <x-ui.navlist.item icon="plus-circle" :href="route('site.create')"
                        :current="request()->routeIs('site.create')" wire:navigate>
                        {{ __('New Site') }}
                    </x-ui.navlist.item>
                    <x-ui.navlist.item icon="plus-circle" :href="route('site.import_new_sites')"
                        :current="request()->routeIs('site.import_new_sites')" wire:navigate>
                        {{ __('Import New Sites') }}
                    </x-ui.navlist.item>
                    <x-ui.navlist.item icon="battery-50" :href="route('site.import_new_batteries')"
                        :current="request()->routeIs('site.import_new_batteries')" wire:navigate>
                        {{ __('Import New Batteries') }}
                    </x-ui.navlist.item>
                    <x-ui.navlist.item icon="arrow-down-tray" :href="route('site.CE.sites')"
                        :current="request()->routeIs('site.CE.sites')" wire:navigate>
                        {{ __('CE Sites') }}
                    </x-ui.navlist.item>
                </x-ui.navlist.group>

                <x-ui.navlist.group expandable :heading="__('Modifications')"
                    :expanded="request()->routeIs('modification.*', 'pos')">
                    <x-ui.navlist.item icon="magnifying-glass" :href="route('modification.search')"
                        :current="request()->routeIs('modification.search')" wire:navigate>
                        {{ __('Search') }}
                    </x-ui.navlist.item>
                    {{-- <x-ui.navlist.item icon="magnifying-glass" href="#">
                        {{ __('Price List') }}
                    </x-ui.navlist.item> --}}
                    {{-- <x-ui.navlist.item icon="plus-circle" :href="route('pos')"
                        :current="request()->routeIs('pos')" wire:navigate>
                        {{ __('POs') }}
                    </x-ui.navlist.item> --}}
                </x-ui.navlist.group>
            </x-ui.navlist>

            <x-ui.spacer />

            {{-- desktop account menu --}}
            <x-ui.dropdown class="hidden w-full lg:block" position="top" align="start">
                <x-ui.profile :name="auth()->user()->name" :email="auth()->user()->email"
                    :initials="auth()->user()->initials()" />

                <x-ui.menu>
                    <x-ui.menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </x-ui.menu.item>

                    <x-ui.menu.separator />

                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="contents">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 text-error">
                                <x-icons.arrow-right-start-on-rectangle class="size-4 shrink-0" />
                                <span class="grow text-start">{{ __('Log Out') }}</span>
                            </button>
                        </form>
                    </li>
                </x-ui.menu>
            </x-ui.dropdown>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">

            {{-- mobile top bar --}}
            <header
                class="sticky top-0 z-30 flex items-center gap-2 border-b border-base-300 bg-base-100 px-4 py-2 lg:hidden">
                <button type="button" class="btn btn-ghost btn-square btn-sm" x-on:click="sidebar = true"
                    aria-label="{{ __('Open menu') }}">
                    <x-icons.bars-2 class="size-5" />
                </button>

                <x-ui.spacer />

                <x-ui.dropdown position="bottom" align="end">
                    <x-ui.profile :initials="auth()->user()->initials()" class="w-auto" />

                    <x-ui.menu>
                        <li class="menu-title px-2 py-1">
                            <span class="block truncate text-sm font-semibold text-base-content">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="block truncate text-xs font-normal">{{ auth()->user()->email }}</span>
                        </li>

                        <x-ui.menu.separator />

                        <x-ui.menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </x-ui.menu.item>

                        <x-ui.menu.separator />

                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="contents">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 text-error">
                                    <x-icons.arrow-right-start-on-rectangle class="size-4 shrink-0" />
                                    <span class="grow text-start">{{ __('Log Out') }}</span>
                                </button>
                            </form>
                        </li>
                    </x-ui.menu>
                </x-ui.dropdown>
            </header>

            {{ $slot }}
        </div>
    </div>

    <x-toaster-hub />

    @livewire('modal-manager')

    @filamentScripts
</body>

</html>
