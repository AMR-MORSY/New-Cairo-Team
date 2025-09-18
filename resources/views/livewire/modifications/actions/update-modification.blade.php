<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Update Modification')" :subheading="__('You will find very important information about the modification action here. ')">


        <x-slot:links>
            <livewire:modifications.modification-links :site="$site" :modification="$modification" />
        </x-slot:links>
        @if (session('quotation_error'))
            <flux:callout icon="exclamation-triangle" variant="secondary" inline>
                <flux:callout.heading>Update issue detected</flux:callout.heading>
                <flux:callout.text>You must submit a quotation first to be able to make this modification status either
                    waiting D6 or Done.</flux:callout.text>

            </flux:callout>
        @endif



        <x-modification-create-form target="Update" :readonly="false" :actions="$actions" :modificationStatus="$modificationStatus"
            :modification="$modification" :subcontractors="$subcontractors" :projects="$projects" :requesters="$requesters" />



    </x-viewLayouts.main-view-layout>
</section>
