 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <flux:navlist>

         @if (request()->routeIs('siteData.update'))
           
             <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </flux:navlist.item>
               <flux:navlist.item :href="route('site.siteData',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.siteData'))
             <flux:navlist.item :href="route('siteData.update',$powerData->id)" wire:navigate>
                 {{ __('Update') }}
             </flux:navlist.item>
               <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('siteData.create'))
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @endif





     </flux:navlist>


 </flux:navlist.group>
