<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="$site->site_code . '-' . $site->site_name" :subheading="__('You can update the modification action here. ')">


        <x-slot:links>
            <livewire:modifications.modification-links :site="$site"  page="create" />
        </x-slot:links>

        <x-modification-creation-form target="New" :readonly="false" :actions="$actions" :modification="null"
            :modificationStatus="$modificationStatus" :subcontractors="$subcontractors" :projects="$projects" :requesters="$requesters" />
    </x-viewLayouts.main-view-layout>
</section>
