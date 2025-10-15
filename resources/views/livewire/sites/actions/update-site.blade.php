 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Site Update')" :subheading="__('You will find very important information about the site here. ')">
         <x-slot:links>
             <x-site-navlinks-group :site="$site" />
         </x-slot:links>
      

         <x-site-creation-form target="Update" :readonly="false" :zones="$zones" :teams="$teams" />
     </x-viewLayouts.main-view-layout>
 </section>
