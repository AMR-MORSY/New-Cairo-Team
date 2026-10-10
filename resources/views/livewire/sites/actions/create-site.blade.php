 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Site Create')" :subheading="__('You will find very important information about the site here. ')">
         <x-slot:links>
             <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
                 <x-ui.navlist.item wire:click="goBack" class=" cursor-pointer">
                     {{ __('BACK') }}
                 </x-ui.navlist.item>
             </x-ui.navlist.group>
         </x-slot:links>
         <x-site-creation-form target="Create" :readonly="false" :zones="$zones" :teams="$teams" />



     </x-viewLayouts.main-view-layout>
 </section>
