<div class="tabs tabs-border">
    {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
    <input type="radio" name="my_tabs_2" class="tab" aria-label="Roles" checked="checked" />


    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Roles')" :subheading="__('You will find very important information about the roles here. ')">
                {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}

                <livewire:tables.users.permissions-table :data="$roles" dataType="roles" />


            </x-viewLayouts.main-view-layout>
        </section>
    </div>



    {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

    <input type="radio" name="my_tabs_2" class="tab" aria-label="Create Role" />
    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Roles')" :subheading="__('You will find very important information about the site here. ')">
                {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}

                <form class="p-6 space-y-8" wire:submit="createRole">
                    <flux:field>
                        <flux:label>Role *</flux:label>
                        <flux:input wire:model="newRole" placeholder="Role" class="w-full" />
                        <flux:error name="newRole" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Permission *</flux:label>
                        <x-select wire:model="newPermissions"
                            placeholder="More than one permission could be assigned to the created Role" multiselect
                            :options="$permissions" option-label="name" option-value="name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Team *</flux:label>
                        <flux:select wire:model="team_id" class="w-full">
                            <flux:select.option :value="null" selected>Role could be assigned to area or
                                considered generic</flux:select.option>
                            @foreach ($teams as $team)
                                <flux:select.option :value="$team->id">{{ $team->code }}</flux:select.option>
                            @endforeach


                        </flux:select>
                        <flux:error name="team_id" />
                    </flux:field>

                    {{-- <x-select wire:model="team_id"
                        placeholder="Role could be assigned to multiple areas or considered generic" multiselect
                        :options="$areas" option-label="code" option-value="id" /> --}}

                    <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                        Create</flux:button>
                </form>



            </x-viewLayouts.main-view-layout>
        </section>
    </div>
</div>
