 <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <x-ui.navlist>

         @if (request()->routeIs('powerData.update'))
           
             <x-ui.navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </x-ui.navlist.item>
               <x-ui.navlist.item :href="route('site.powerData',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('site.powerData'))
             <x-ui.navlist.item :href="route('powerData.update',$powerData->id)" wire:navigate>
                 {{ __('Update') }}
             </x-ui.navlist.item>
               <x-ui.navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('powerData.create'))
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @endif





     </x-ui.navlist>


 </x-ui.navlist.group>
