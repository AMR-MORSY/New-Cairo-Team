 <x-viewLayouts.main-view-layout :heading="__('Sites')" :subheading="__('You will find the results of the searching here.Sites with corresponding zones are shown below  ')">


    
     @if (count($sites) > 0)
         <div class="max-w-xl xl:max-w-2xl  2xl:max-w-7xl">
             <livewire:tables.site.searched-sites-table :props="$props" :key="'search-' . now()" />
         </div>
     @else
         <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
             <x-ui.callout.heading>No sites found</x-ui.callout.heading>
             <x-ui.callout.text>click the button to go to new site creation page.</x-ui.callout.text>
             <x-slot name="actions">
                 <x-ui.button :href="route('site.create')" wire:navigate>NEW SITE
                 </x-ui.button>

             </x-slot>
         </x-ui.callout>
     @endif
 </x-viewLayouts.main-view-layout>
