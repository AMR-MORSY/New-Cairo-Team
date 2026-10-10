<div class="tabs tabs-border">
    {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
    <input type="radio" name="my_tabs_2" class="tab" aria-label="{{ $site->site_code }}-{{ $site->site_name }}"
        checked="checked" />


    <div class="tab-content min-w-0 border-2 border-gray-200 bg-base-100 p-4 md:p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Site Details')" :subheading="__('You will find very important information about the site here. ')">
                <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links>

                <x-site-creation-form target="Information" :readonly="true" />



            </x-viewLayouts.main-view-layout>
        </section>
    </div>



    {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

    <input type="radio" name="my_tabs_2" class="tab" aria-label="Routes" />

    <div class="tab-content min-w-0 border-2  border-gray-200 bg-base-100 p-4 md:p-10">

        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Routes')" :subheading="__('You will find very important information about the site here. ')">
                <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links>

                {{-- @if ($route)
                    <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                        <x-ui.callout.heading>Route</x-ui.callout.heading>
                        <x-ui.callout.text>{{$route}}</x-ui.callout.text>

                    </x-ui.callout>
                @endif --}}

                {{-- the cascades table holds one chain per site; the sheet can hold several --}}
                <x-site-sheet-routes :grids="$sheetRouteGrids" :cascade-count="$sheetCascadeCount"
                    :current="$site->site_code" :is-hub="$isSheetHub" />

                <div class=" w-full ">

                    {{-- <livewire:tables.site.cascades-table :site="$site" cascadesType='direct' /> --}}
                      <livewire:tables.site.cascades-table :site="$site"  />
                </div>

                {{-- <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>{{ $site->site_code }}-{{ $site->site_name }}</x-ui.callout.heading>
                    <x-ui.callout.text>click the button to go to cascades update page.</x-ui.callout.text>
                    <x-slot name="actions">
                        <x-ui.button :href="route('site.cascades.update',['site'=>$site->site_code])">UPDATE CASCADES
                        </x-ui.button>
                    </x-slot>
                </x-ui.callout> --}}
                <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>{{ $site->site_code }}-{{ $site->site_name }}</x-ui.callout.heading>
                    <x-ui.callout.text>click the button to view the site's Mux plan.</x-ui.callout.text>
                    <x-slot name="actions">
                        <x-ui.button wire:click="muxPlan">MUX PLAN
                        </x-ui.button>
                    </x-slot>
                </x-ui.callout>
                <livewire:tables.site.mux-plans-table :muxPlans="$muxPlans" />
            </x-viewLayouts.main-view-layout>
    </div>

    {{-- @if ($newDirectCascades) --}}
    {{-- <span class="indicator-item badge">@php
        echo $site->cascades()->count();
    @endphp</span> --}}
    {{-- @endif --}}

    {{-- //////////////////////////indirect cascades view///////////////////////////////  --}}


    {{-- <input type="radio" name="my_tabs_2" class="tab" aria-label="In.Cascades" /> --}}

    {{-- <div class="tab-content min-w-0 border-2  border-gray-200 bg-base-100 p-4 md:p-10"> --}}
        {{-- <section class="w-full"> --}}
            {{-- <x-viewLayouts.main-view-layout :heading="__('Indirect Cascade')" :subheading="__('You will find very important information about the site here. ')">
                <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}
                {{-- @if ($indirectCascades) --}}
                {{-- <div class=" w-full "> --}}
                    {{-- <livewire:site-indirect-cascades-table :data=$indirectCascades /> --}}
                    {{-- <livewire:tables.site.cascades-table :site="$site" cascadesType='indirect' /> --}}
                {{-- </div> --}}
                {{-- @endif --}}
            {{-- </x-viewLayouts.main-view-layout> --}}
    {{-- </div> --}}

    {{-- @if ($indirectCascades)
        <span class="indicator-item badge">@php
            echo count($indirectCascades);
        @endphp</span>
    @endif --}}

    {{-- //////////////////////////sheet view/////////////////////////////// --}}

    {{-- <input type="radio" name="my_tabs_2" class="tab" aria-label="Sheet" /> --}}

    {{-- <div class="tab-content min-w-0 border-2 border-gray-200 bg-base-100 p-4 md:p-10"> --}}
        {{-- <section class="w-full"> --}}
            {{-- <x-viewLayouts.main-view-layout :heading="__('Planning Sheet')" :subheading="__('This site and everything cascading off it, exactly as the sheet draws it.')"> --}}
                {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}

                {{-- <x-site-sheet-view :grid="$sheetGrid" :current="$site->site_code"
                    :imported-at="$sheetImportedAt" :source="$sheetSource" class="mt-5" /> --}}
            {{-- </x-viewLayouts.main-view-layout> --}}
        {{-- </section> --}}
    {{-- </div> --}}
</div>
