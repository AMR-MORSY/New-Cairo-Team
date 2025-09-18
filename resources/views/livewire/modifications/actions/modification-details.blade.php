<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Modification Details')" :subheading="__('You will find very important information about the modification action here. ')">


        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modification="$modification" />
        </x-slot:links>
      



        <x-modification-create-form target="Details" :readonly="true" :actions="$actions" :modificationStatus="$modificationStatus"
            :modification="$modification" :subcontractors="$subcontractors" :projects="$projects" :requesters="$requesters" />



    </x-viewLayouts.main-view-layout>
</section>
