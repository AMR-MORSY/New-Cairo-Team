<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Update Site Note')" :subheading="__('You will find very important information about the power data of site') .
        $site->site_code .
        '-' .
        $site->site_name">

        <x-slot:links>
            <livewire:sites.site-notes-links :site="$site" />
        </x-slot:links>
        <x-site-note-creation-form target="Update" :readonly="false" :site="$site" />



    </x-viewLayouts.main-view-layout>
</section>
