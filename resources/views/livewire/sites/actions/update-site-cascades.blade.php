 <x-viewLayouts.main-view-layout :heading="__('Site Cascades')" :subheading="__(
     'You will find very important information about ' . $site->site_code . '-' . $site->site_name . ' cascades',
 )">


     <x-slot:links>
         <x-ui.navlist class="mt-20 lg:grid">
             <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">
                 <x-ui.navlist.item :href="route('site.show', $site->site_code)" class=" cursor-pointer" wire:navigate>
                     {{ __('BACK') }}
                 </x-ui.navlist.item>
             </x-ui.navlist.group>
         </x-ui.navlist>
     </x-slot:links>

     <div class="max-w-xl xl:max-w-3xl  2xl:max-w-7xl">
         <livewire:tables.site.cascades-table :site="$site" cascadesType='updateCascades' />


         <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
             <x-ui.callout.heading :href="route('site.show', ['site' => $site->site_code])" wire:navigate
                 class=" cursor-pointer">{{ $site->site_code }}-{{ $site->site_name }}</x-ui.callout.heading>
             <x-ui.callout.text>Search for sites to add them into the above cascaded sites list.</x-ui.callout.text>
             <x-slot name="actions">
                 <livewire:sites.searching-for-site target='site.cascades.update' :updateSite="$site->site_code" />

             </x-slot>
         </x-ui.callout>
     </div>




 </x-viewLayouts.main-view-layout>
