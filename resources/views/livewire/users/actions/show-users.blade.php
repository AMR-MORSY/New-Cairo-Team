 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Users')" :subheading="__('You will find very important information about the site here. ')">
         <x-slot:links>
             <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
                 <flux:navlist.item wire:click="goBack" class=" cursor-pointer" wire:navigate>
                     {{ __('BACK') }}
                 </flux:navlist.item>
             </flux:navlist.group>
         </x-slot:links>

         <div class="max-w-xl xl:max-w-3xl  2xl:max-w-7xl">


             <livewire:tables.users.all-users-table :users="$users" />
         </div>

     </x-viewLayouts.main-view-layout>
 </section>
