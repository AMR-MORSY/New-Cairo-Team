<div class="tabs tabs-border">
    {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
    <input type="radio" name="my_tabs_2" class="tab" aria-label="Permissions" checked="checked" />


    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Permissions')" :subheading="__('You will find very important information about the permissions here. ')">
            {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}

            <livewire:tables.users.permissions-table :data="$permissions" dataType="permissions" />



            </x-viewLayouts.main-view-layout>
        </section>
    </div>



    {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

    <input type="radio" name="my_tabs_2" class="tab" aria-label="Create Permission" />
    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Permissions')" :subheading="__('You will find very important information about the site here. ')">
                {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}

                <form class="p-6 space-y-8" wire:submit="createPermission">
                    <flux:field>
                        <flux:label>Permission *</flux:label>
                        <flux:input wire:model="permit" 
                            placeholder="Permission" class="w-full" />
                        <flux:error name="permit" />
                    </flux:field>

                      <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                        Create</flux:button>
                </form>



            </x-viewLayouts.main-view-layout>
        </section>
    </div>
</div>
