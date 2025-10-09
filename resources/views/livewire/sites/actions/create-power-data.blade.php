<section class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Create Power Data')" :subheading="__('You will find very important information about the power data of site').$site->site_code.'-'.$site->site_name">
       
         <x-slot:links>
             <livewire:sites.power-data-links :site="$site" :powerData="null" />
         </x-slot:links>
         <x-site-power-data-creation-form target="New" :readonly="false"  :site="$site" />



     </x-viewLayouts.main-view-layout>
 </section>
