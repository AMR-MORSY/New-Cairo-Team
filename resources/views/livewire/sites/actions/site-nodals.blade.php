 {{-- <x-layouts.app :title="__('Nodal Sites')"> --}}
 <div class="w-full">
     <x-viewLayouts.main-view-layout :heading="__('Nodal Sites')" :subheading="__('You will find very important information about the batteries in site')">


         <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
             <flux:callout.heading>Cairo East Nodal sites</flux:callout.heading>
             <flux:callout.text>click the button to view CE nodal sites with batteries.</flux:callout.text>
             <x-slot name="actions">
                 <flux:button wire:click="CENodalSites">CE Nodal sites
                 </flux:button>
             </x-slot>
         </flux:callout>
         <livewire:tables.site.nodal-sites-table :nodalSites="$nodalSites"/>
     </x-viewLayouts.main-view-layout>
 </div>
 {{-- </x-layouts.app> --}}
