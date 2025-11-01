<div>
    <x-viewLayouts.main-view-layout :heading="__('Roles')" :subheading="__('You will find very important information about the Roles here. ')">
        <x-slot:links >
            <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
                <flux:navlist.item :href="route('role.index')" wire:navigate>
                    {{ __('BACK') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </x-slot:links>
        <flux:heading class=" mt-10">{{$role->name}}</flux:heading>
        <livewire:tables.users.permissions-table :data="$permissions" dataType="rolePermissions" />
    </x-viewLayouts.main-view-layout>
</div>
