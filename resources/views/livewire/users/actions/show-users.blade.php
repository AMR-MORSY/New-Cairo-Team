 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Users')" :subheading="__('You will find very important information about the site here. ')">
         {{-- <x-slot:links>
             <x-site-navlinks-group :site="$site" />
         </x-slot:links> --}}



         <livewire:tables.users.all-users-table :users="$users" />

     </x-viewLayouts.main-view-layout>
 </section>
