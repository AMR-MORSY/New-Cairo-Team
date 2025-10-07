 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Create New Battery Record')" :subheading="__('You will find very important information about the batteries here. ')">
         {{-- <x-slot:links>
             <x-site-navlinks-group :site="$site" />
         </x-slot:links> --}}
         {{-- <livewire:Sites.SiteCreationForm :readonly="false" :site="$site" target="Update" /> --}}
         <x-battery-creation-form target="New" :readonly="false" :categories="$categories"/>

      

     </x-viewLayouts.main-view-layout>
 </section>
