 <section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Update Battery Record')" :subheading="__('You will find very important information about the batteries in site ').$site->site_code.'-'.$site->site_name">
           <x-slot:links>
             <livewire:sites.batteries-links :site="$site" :battery="$battery" />
         </x-slot:links>
         <x-battery-creation-form target="Update" :readonly="false" :categories="$categories" :site="$site"/>

      

     </x-viewLayouts.main-view-layout>
 </section>
