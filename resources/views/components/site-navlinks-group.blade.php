 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid">
     <flux:navlist>
         <flux:navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
             {{ __('Site Details') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.update',$site->site_code)" wire:navigate>
             {{ __('Update') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.modifications',$site->site_code)" wire:navigate>
             {{ __('Modifications') }}
         </flux:navlist.item>
         <flux:navlist.item href="#" wire:navigate>{{ __('NUR') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.batteries',$site->site_code)" wire:navigate>
             {{ __('Batteries Data') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.siteData',$site->site_code)" wire:navigate>
             {{ __('Site Data') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.powerData',$site->site_code)" wire:navigate>
             {{ __('Power Data') }}
         </flux:navlist.item>
         <flux:navlist.item :href="route('site.notes',$site->site_code)" wire:navigate>
             {{ __('Notes') }}
         </flux:navlist.item>
     </flux:navlist>
 </flux:navlist.group>
