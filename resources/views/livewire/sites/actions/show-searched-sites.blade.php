 <x-viewLayouts.main-view-layout :heading="__('Sites')" :subheading="__('You will find very important information about the batteries in site ')">


     <x-slot:links>
         <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-10">
             <flux:navlist.item  wire:click="goBack" class=" cursor-pointer">
                 {{ __('BACK') }}
             </flux:navlist.item>
         </flux:navlist.group>
     </x-slot:links>
     @if (count($sites) > 0)
         <livewire:tables.site.searched-sites-table :props="$props" :key="'search-' . now()" />
     @else
         <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
             <flux:callout.heading>No sites found</flux:callout.heading>
             <flux:callout.text>click the button to go to new site creation page.</flux:callout.text>
             <x-slot name="actions">
                 <flux:button :href="route('site.create')" wire:navigate>NEW SITE
                 </flux:button>

             </x-slot>
         </flux:callout>
     @endif
 </x-viewLayouts.main-view-layout>
