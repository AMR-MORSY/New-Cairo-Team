 <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <x-ui.navlist>

         @if (request()->routeIs('site.note.update'))
             <x-ui.navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.notes',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('site.notes'))
          
           <x-ui.navlist.item :href="route('site.note.create',$site->site_code)" wire:navigate>
                 {{ __('NEW') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('site.note.create'))
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @endif





     </x-ui.navlist>


 </x-ui.navlist.group>
