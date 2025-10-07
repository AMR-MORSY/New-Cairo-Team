 <flux:navlist.group expandable heading="Quick Links" class=" lg:grid mt-20">

     @if (request()->routeIs('site.modifications'))
         {{-- @can('createModification', $site) --}}
             <flux:navlist>


                 <flux:navlist.item :href="route('modification.create',$site->site_code)" wire:navigate>
                     {{ __('New Modification') }}
                 </flux:navlist.item>


             </flux:navlist>
         {{-- @endcan --}}
     @else
         <flux:navlist>

             <flux:navlist.item :href="route('modification.details',$modification->id)" wire:navigate>
                 {{ __('Modification Details') }}
             </flux:navlist.item>
             <flux:navlist.item :href="route('modification.create',$modification->site_code)" wire:navigate>
                 {{ __('New Modification') }}
             </flux:navlist.item>

             @if (!request()->routeIs('modification.update'))
                 <flux:navlist.item :href="route('modification.update',$modification->id)" wire:navigate>
                     {{ __('Update modification') }}
                 </flux:navlist.item>
             @endif
             <flux:navlist.item wire:click="delete" class=" cursor-pointer">
                 {{ __('Delete modification') }}
             </flux:navlist.item>

             @if (!request()->routeIs('quotation.details'))
                 <flux:navlist.item :href="route('quotation.details',$modification->id)" wire:navigate>
                     {{ __('Quotation') }}
                 </flux:navlist.item>
             @endif


         </flux:navlist>
     @endif

 </flux:navlist.group>
