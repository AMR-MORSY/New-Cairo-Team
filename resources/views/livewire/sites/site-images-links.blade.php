 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <flux:navlist>

         @if (request()->routeIs('site.images.update'))
            
             <flux:navlist.item :href="route('site.images',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.images'))
          
           <flux:navlist.item :href="route('site.images.upload',$site->site_code)" wire:navigate>
                 {{ __('Upload') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.images.update',$site->site_code)" wire:navigate>
                 {{ __('Update') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.images.upload'))
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @endif





     </flux:navlist>


 </flux:navlist.group>
