<div>
    <x-viewLayouts.main-view-layout :heading="__('Roles')" :subheading="__('You will find very important information about the Roles here. ')">
        <x-slot:links >
            <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
                <x-ui.navlist.item :href="route('role.index')" wire:navigate>
                    {{ __('BACK') }}
                </x-ui.navlist.item>
            </x-ui.navlist.group>
        </x-slot:links>
        <x-ui.heading class=" mt-10">{{$role->name}}</x-ui.heading>
        <livewire:tables.users.permissions-table :data="$permissions" dataType="rolePermissions" />
    </x-viewLayouts.main-view-layout>
</div>
