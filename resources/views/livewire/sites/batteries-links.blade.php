<div>
    <x-ui.navlist class="mt-20 lg:grid">
        <x-ui.navlist.group expandable :heading="__('Quick Links')" :expanded="true">


    
          @if ($page === 'show')
             <x-ui.navlist.item :href="route('battery.update',$battery->id)" wire:navigate>
                 {{ __('Update') }}
             </x-ui.navlist.item>

             <x-ui.navlist.item wire:click="mountAction('delete')" class=" cursor-pointer">
                 {{ __('Delete') }}
             </x-ui.navlist.item>
              <x-ui.navlist.item :href="route('site.batteries',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @elseif ($page === 'update.battery')
          <x-ui.navlist.item :href="route('battery.show',$battery->id)" wire:navigate>
                 {{ __('Back To Details') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item wire:click="mountAction('delete')" class=" cursor-pointer">
                 {{ __('Delete') }}
             </x-ui.navlist.item>
         @elseif ($page === 'site.batteries')
             <x-ui.navlist.item :href="route('battery.create',$site->site_code)" wire:navigate>
                 {{ __('New Record') }}
             </x-ui.navlist.item>
             <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @elseif ($page === 'create')
              <x-ui.navlist.item :href="route('site.show',$site->site_code)" wire:navigate>
                 {{ __('Back') }}
             </x-ui.navlist.item>
         @endif





    


 </x-ui.navlist.group>
    </x-ui.navlist>

      <x-filament-actions::modals />
</div>