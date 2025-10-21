<div class="tabs tabs-border">
    {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
    <input type="radio" name="my_tabs_2" class="tab" aria-label="{{ $site->site_code }}-{{ $site->site_name }}"
        checked="checked" />


    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

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

    <input type="radio" name="my_tabs_2" class="tab" aria-label="D.Cascades" />

    <div class="tab-content border-2  border-gray-200 bg-base-100 p-10">
        @if ($newDirectCascades)
            <div class=" w-full ">
                {{-- <livewire:components.cascades-table :cascades=$newDirectCascades cascadesType='direct' /> --}}
                <livewire:tables.site.cascades-table :cascades=$newDirectCascades cascadesType='direct' />
            </div>
        @endif
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>{{ $site->site_code }}-{{ $site->site_name }}</flux:callout.heading>
            <flux:callout.text>click the button to go to cascades update page.</flux:callout.text>
            <x-slot name="actions">
                <flux:button :href="route('site.cascades.update',['site'=>$site->site_code])">UPDATE CASCADES
                </flux:button>
            </x-slot>
        </flux:callout>
        <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
            <flux:callout.heading>{{ $site->site_code }}-{{ $site->site_name }}</flux:callout.heading>
            <flux:callout.text>click the button to view the site's Mux plan.</flux:callout.text>
            <x-slot name="actions">
                <flux:button wire:click="muxPlan">MUX PLAN
                </flux:button>
            </x-slot>
        </flux:callout>
        <livewire:tables.site.mux-plans-table :muxPlans="$muxPlans" />
    </div>

    @if ($newDirectCascades)
        <span class="indicator-item badge">@php
            echo count($newDirectCascades);
        @endphp</span>
    @endif

    {{-- //////////////////////////indirect cascades view///////////////////////////////  --}}


    <input type="radio" name="my_tabs_2" class="tab" aria-label="In.Cascades" />

    <div class="tab-content border-2  border-gray-200 bg-base-100 p-10">
        @if ($indirectCascades)
            <div class=" w-full ">
                {{-- <livewire:site-indirect-cascades-table :data=$indirectCascades /> --}}
                <livewire:tables.site.cascades-table :cascades=$indirectCascades cascadesType='indirect' />
            </div>
        @endif
    </div>

    @if ($indirectCascades)
        <span class="indicator-item badge">@php
            echo count($indirectCascades);
        @endphp</span>
    @endif
</div>
