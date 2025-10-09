<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Battery Details')" :subheading="__('You will find very important information about the batteries in site ').$site->site_code.'-'.$site->site_name">


        <x-slot:links>
            <livewire:sites.batteries-links :site="$site" :battery="$battery" />
        </x-slot:links>
          <x-battery-creation-form :target="null" :readonly="true" :categories="null" :site="$site"/>
    </x-viewLayouts.main-view-layout>
</section>
