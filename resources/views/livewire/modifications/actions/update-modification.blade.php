<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="$site->site_code.'-'.$site->site_name" :subheading="__('You can update the modification action here. ')">


        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modification="$modification" page="update" />
        </x-slot:links>
        @if (session('quotation_error'))
            <x-ui.callout icon="exclamation-triangle" variant="secondary" inline>
                <x-ui.callout.heading>Update issue detected</x-ui.callout.heading>
                <x-ui.callout.text>You must submit a quotation first to be able to make this modification status either
                    waiting D6 or Done.</x-ui.callout.text>

            </x-ui.callout>
        @endif



        <x-modification-creation-form target="Update" :readonly="false" :actions="$actions" :modificationStatus="$modificationStatus"
            :modification="$modification" :subcontractors="$subcontractors" :projects="$projects" :requesters="$requesters" />



    </x-viewLayouts.main-view-layout>
</section>
