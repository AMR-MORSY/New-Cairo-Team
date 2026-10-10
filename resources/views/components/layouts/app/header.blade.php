{{-- Alternate top-bar shell from the Laravel starter kit. Nothing references
     it today (the app uses layouts.app.sidebar); kept for reference. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-svh bg-base-200 text-base-content antialiased" x-data="{ sidebar: false }">
    <header
        class="flex items-center gap-2 border-b border-base-300 bg-base-100 px-4 py-2">
        <button type="button" class="btn btn-ghost btn-square btn-sm lg:hidden" x-on:click="sidebar = true"
            aria-label="{{ __('Open menu') }}">
            <x-icons.bars-2 class="size-5" />
        </button>

        <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center gap-2 lg:ms-0" wire:navigate>
            <x-app-logo />
        </a>

        <x-ui.navbar class="max-lg:hidden">
            <x-ui.navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </x-ui.navbar.item>
        </x-ui.navbar>

        <x-ui.spacer />

        <x-ui.navbar class="me-1.5">
            <x-ui.tooltip :content="__('Search')" position="bottom">
                <x-ui.navbar.item icon="magnifying-glass" href="#" aria-label="{{ __('Search') }}" />
            </x-ui.tooltip>
            <x-ui.tooltip :content="__('Repository')" position="bottom">
                <x-ui.navbar.item class="max-lg:hidden" icon="folder-git-2"
                    href="https://github.com/laravel/livewire-starter-kit" target="_blank"
                    aria-label="{{ __('Repository') }}" />
            </x-ui.tooltip>
            <x-ui.tooltip :content="__('Documentation')" position="bottom">
                <x-ui.navbar.item class="max-lg:hidden" icon="book-open-text"
                    href="https://laravel.com/docs/starter-kits#livewire" target="_blank"
                    aria-label="{{ __('Documentation') }}" />
            </x-ui.tooltip>
        </x-ui.navbar>

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

    {{-- mobile off-canvas menu --}}
    <div x-show="sidebar" x-cloak x-transition.opacity x-on:click="sidebar = false"
        class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

    <aside x-bind:class="sidebar ? 'translate-x-0' : '-translate-x-full'"
        x-on:keydown.escape.window="sidebar = false"
        class="fixed inset-y-0 start-0 z-50 flex w-72 flex-col gap-4 overflow-y-auto border-e border-base-300 bg-base-100 p-4 transition-transform duration-200 lg:hidden">

        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2" wire:navigate>
                <x-app-logo />
            </a>
            <button type="button" class="btn btn-ghost btn-square btn-sm" x-on:click="sidebar = false"
                aria-label="{{ __('Close menu') }}">
                <x-icons.x-mark class="size-5" />
            </button>
        </div>

        <x-ui.navlist>
            <x-ui.navlist.group :heading="__('Platform')">
                <x-ui.navlist.item icon="layout-grid" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-ui.navlist.item>
            </x-ui.navlist.group>
        </x-ui.navlist>

        <x-ui.spacer />

        <x-ui.navlist>
            <x-ui.navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </x-ui.navlist.item>

            <x-ui.navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </x-ui.navlist.item>
        </x-ui.navlist>
    </aside>

    <main class="flex-1 p-4 lg:p-6">
        {{ $slot }}
    </main>
</body>

</html>
