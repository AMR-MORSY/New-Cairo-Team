 <x-ui.navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <x-ui.navlist>

         @if (request()->routeIs('site.images.update'))
            
             <x-ui.navlist.item :href="route('site.images',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('site.images'))
          
           <x-ui.navlist.item :href="route('site.images.upload',$site->site_code)" wire:navigate>
                 {{ __('Upload') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.images.update',$site->site_code)" wire:navigate>
                 {{ __('Update') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @elseif (request()->routeIs('site.images.upload'))
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </x-ui.navlist.item>
         @endif





     </x-ui.navlist>


 </x-ui.navlist.group>
