{{-- Same shape as the dashboard sidebar in components/layouts/app/sidebar.blade.php:
     one <x-ui.navlist> owning the group, not a group wrapped around a second
     list - navlist.group renders an <li>, so it needs the <ul> above it. --}}
<div>
    <x-ui.navlist class="mt-20 lg:grid">
        <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">

            @if ($page === 'modifications')
                <x-ui.navlist.item icon="plus-circle" :href="route('modification.create', $site->site_code)" wire:navigate>
                    {{ __('New') }}
                </x-ui.navlist.item>

                <x-ui.navlist.item icon="arrow-left" :href="route('site.show', $site->site_code)" wire:navigate>
                    {{ __('Back') }}
                </x-ui.navlist.item>

            @elseif ($page === 'details')
                <x-ui.navlist.item icon="pencil-square" :href="route('modification.update', $modification->id)"
                    wire:navigate>
                    {{ __('Update') }}
                </x-ui.navlist.item>

                {{-- mountAction() opens Filament's modal; rendering
                     {{ $this->deleteAction }} instead would replace this
                     navlist row with a Filament button --}}
                <x-ui.navlist.item icon="trash" wire:click="mountAction('delete')"
                    class="cursor-pointer text-error">
                    {{ __('Delete') }}
                </x-ui.navlist.item>

                <x-ui.navlist.item icon="arrow-left" :href="route('site.modifications', $site->site_code)" wire:navigate>
                    {{ __('Back') }}
                </x-ui.navlist.item>

            @elseif ($page === 'update')
                <x-ui.navlist.item icon="eye" :href="route('modification.details', $modification->id)" wire:navigate>
                    {{ __('Details') }}
                </x-ui.navlist.item>

                {{-- mountAction() opens Filament's modal; rendering
                     {{ $this->deleteAction }} instead would replace this
                     navlist row with a Filament button --}}
                <x-ui.navlist.item icon="trash" wire:click="mountAction('delete')"
                    class="cursor-pointer text-error">
                    {{ __('Delete') }}
                </x-ui.navlist.item>

                <x-ui.navlist.item icon="arrow-left" :href="route('site.modifications', $site->site_code)" wire:navigate>
                    {{ __('Back') }}
                </x-ui.navlist.item>
                 @elseif ($page === 'create')
                   <x-ui.navlist.item icon="arrow-left" :href="route('site.modifications', $site->site_code)" wire:navigate>
                    {{ __('Back') }}
                </x-ui.navlist.item>
            @endif

        </x-ui.navlist.group>
    </x-ui.navlist>

    {{-- has to live in the component's own view: the blade reads $this and
         expects it to be the Livewire component implementing HasActions --}}
    <x-filament-actions::modals />
</div>
