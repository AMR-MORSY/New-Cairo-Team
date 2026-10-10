 <x-viewLayouts.main-view-layout :heading="__('Permissions')" :subheading="__('You will find very important information about the permissions here. ')">
     <x-slot:links>
         <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
             <x-ui.navlist.item wire:click="goBack" class=" cursor-pointer" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         </x-ui.navlist.group>
     </x-slot:links>

     <div class="tabs tabs-border">
         {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
         <input type="radio" name="my_tabs_2" class="tab" aria-label="Permissions" checked="checked" />


         <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

             <section class="w-full">


                 <div class="max-w-xl xl:max-w-3xl  2xl:max-w-7xl">
                     <livewire:tables.users.permissions-table :data="$permissions" dataType="permissions" />

                 </div>





             </section>
         </div>



         {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

         <input type="radio" name="my_tabs_2" class="tab" aria-label="Create Permission" />
         <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

             <section class="w-full">

                 <form class="p-6 space-y-8" wire:submit="createPermission">
                     <x-ui.field>
                         <x-ui.label>Permission *</x-ui.label>
                         <x-ui.input wire:model="permit" placeholder="Permission" class="w-full" />
                         <x-ui.error name="permit" />
                     </x-ui.field>

                     <x-ui.button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                         Create</x-ui.button>
                 </form>




             </section>
         </div>
     </div>
 </x-viewLayouts.main-view-layout>
