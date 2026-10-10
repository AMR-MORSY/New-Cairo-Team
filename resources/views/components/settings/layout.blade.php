<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <x-ui.navlist>
            <x-ui.navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Profile') }}</x-ui.navlist.item>
            <x-ui.navlist.item :href="route('settings.password')" wire:navigate>{{ __('Password') }}</x-ui.navlist.item>
            <x-ui.navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Appearance') }}</x-ui.navlist.item>
        </x-ui.navlist>
    </div>

    <x-ui.separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <x-ui.heading>{{ $heading ?? '' }}</x-ui.heading>
        <x-ui.subheading>{{ $subheading ?? '' }}</x-ui.subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
