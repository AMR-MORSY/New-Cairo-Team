 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <flux:navlist>

         @if (request()->routeIs('powerData.update'))
           
             <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </flux:navlist.item>
               <flux:navlist.item :href="route('site.powerData',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.powerData'))
             <flux:navlist.item :href="route('powerData.update',$powerData->id)" wire:navigate>
                 {{ __('Update') }}
             </flux:navlist.item>
               <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('powerData.create'))
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </flux:navlist.item>
         @endif





     </flux:navlist>


 </flux:navlist.group>
