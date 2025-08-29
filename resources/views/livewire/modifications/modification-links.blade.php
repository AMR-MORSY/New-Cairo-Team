 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">
     <flux:navlist>
         @if (count($modifications) > 0)
             <flux:navlist.item :href="route('modification.show',$site->site_code)" wire:navigate>
                 {{ __('Modification Details') }}
             </flux:navlist.item>
         @else
          <flux:navlist.item :href="route('modification.create',$site->site_code)" wire:navigate>
                 {{ __('New Modification') }}
             </flux:navlist.item>
         @endif

         {{-- <flux:navlist.item :href="route('site.update',$site->site_code)" wire:navigate>{{ __('Update') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('modification.show',$site->site_code)" wire:navigate>
             {{ __('Modifications') }}
         </flux:navlist.item>
         <flux:navlist.item href="#" wire:navigate>{{ __('NUR') }}
         </flux:navlist.item>
         <flux:navlist.item href="#" wire:navigate>
             {{ __('Batteries Data') }}
         </flux:navlist.item>
         <flux:navlist.item href="#" wire:navigate>{{ __('Site Data') }}
         </flux:navlist.item>
         <flux:navlist.item href="#" wire:navigate>{{ __('Power Data') }}
         </flux:navlist.item> --}}
     </flux:navlist>
 </flux:navlist.group>
