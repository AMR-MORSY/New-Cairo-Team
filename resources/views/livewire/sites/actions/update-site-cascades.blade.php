 <x-viewLayouts.main-view-layout :heading="__('Site Cascades')" :subheading="__('You will find very important information about '.$site->site_code .'-'. $site->site_name.' cascades')">
     <x-slot:links>
         <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
             <flux:navlist.item :href="route('site.show',$site->site_code)" class=" cursor-pointer" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         </flux:navlist.group>
     </x-slot:links>

     <div class="max-w-xl xl:max-w-3xl  2xl:max-w-7xl">
         <livewire:tables.site.cascades-table :site="$site" cascadesType='updateCascades' />


         <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
             <flux:callout.heading :href="route('site.show',['site'=>$site->site_code])" wire:navigate
                 class=" cursor-pointer">{{ $site->site_code }}-{{ $site->site_name }}</flux:callout.heading>
             <flux:callout.text>Search for sites to add them into the above cascaded sites list.</flux:callout.text>
             <x-slot name="actions">
                 <livewire:sites.searching-for-site target='site.cascades.update' :updateSite="$site->site_code" />

             </x-slot>
         </flux:callout>
     </div>




 </x-viewLayouts.main-view-layout>
