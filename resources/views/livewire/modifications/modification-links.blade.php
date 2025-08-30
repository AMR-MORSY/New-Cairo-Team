 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">
     <flux:navlist>

         <flux:navlist.item :href="route('modification.details',$modification->id)" wire:navigate>
             {{ __('Modification Details') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('modification.create',$modification->site_code)" wire:navigate>
             {{ __('New Modification') }}
         </flux:navlist.item>


         <flux:navlist.item :href="route('modification.update',$modification->id)" wire:navigate>{{ __('Update') }}
         </flux:navlist.item>
         <flux:navlist.item wire:click='delete' class=" cursor-pointer">
             {{ __('Delete') }}
         </flux:navlist.item>

         <flux:navlist.item :href="route('modification.quotation',$modification->id)" wire:navigate>
             {{ __('Quotation') }}
         </flux:navlist.item>

         {{-- <flux:navlist.item :href="route('modification.show',$site->site_code)" wire:navigate>
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
         </flux:navlist.item>  --}}
     </flux:navlist>
 </flux:navlist.group>
