 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Create New Battery Record')" :subheading="__('You will find very important information about the batteries in site').$site->site_code.'-'.$site->site_name">
         {{-- <x-slot:links>
             <x-site-navlinks-group :site="$site" />
         </x-slot:links> --}}
         {{-- <livewire:Sites.SiteCreationForm :readonly="false" :site="$site" target="Update" /> --}}

         <x-slot:links>
             <livewire:sites.batteries-links :site="$site" :battery="null" />
         </x-slot:links>
         <x-battery-creation-form target="New" :readonly="false" :categories="$categories" :site="$site" />



     </x-viewLayouts.main-view-layout>
 </section>
