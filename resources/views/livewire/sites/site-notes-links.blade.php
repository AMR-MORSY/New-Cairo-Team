<div>
    <x-ui.navlist class="mt-20 lg:grid">
        <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">

            @if ($page == 'site.note.update')
                <x-ui.navlist.item wire:click="delete" class=" cursor-pointer">
                    {{ __('Delete') }}
                </x-ui.navlist.item>
                <x-ui.navlist.item :href="route('site.notes', $site->site_code)" wire:navigate>
                    {{ __('BACK') }}
                </x-ui.navlist.item>
            @elseif ($page == 'site.notes')
                <x-ui.navlist.item :href="route('site.note.create', $site->site_code)" wire:navigate>
                    {{ __('NEW') }}
                </x-ui.navlist.item>
                <x-ui.navlist.item :href="route('site.show', $site->site_code)" wire:navigate>
                    {{ __('BACK') }}
                </x-ui.navlist.item>
            @elseif ($page == 'create-site-note')
                <x-ui.navlist.item :href="route('site.show', $site->site_code)" wire:navigate>
                    {{ __('BACK') }}
                </x-ui.navlist.item>
            @endif
        </x-ui.navlist.group>




    </x-ui.navlist>
      <x-filament-actions::modals />
</div>
