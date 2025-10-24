 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">


     <flux:navlist>

         @if (request()->routeIs('site.note.update'))
             <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.notes',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.notes'))
          
           <flux:navlist.item :href="route('site.note.create',$site->site_code)" wire:navigate>
                 {{ __('NEW') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @elseif (request()->routeIs('site.note.create'))
             <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('BACK') }}
             </flux:navlist.item>
         @endif





     </flux:navlist>


 </flux:navlist.group>
