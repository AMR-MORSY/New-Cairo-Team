{{-- <x-layouts.app :title="__('Site | Search')"> --}}

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
                <livewire:Sites.SiteCreationForm :readonly="true" :site="$site" target="Information" />

            </x-viewLayouts.main-view-layout>
        </section>
    </div>



    {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

    <input type="radio" name="my_tabs_2" class="tab" aria-label="D.Cascades" />

    <div class="tab-content border-2  border-gray-200 bg-base-100 p-10">
        @if ($newDirectCascades)
            <div class=" w-full ">
                <livewire:site-direct-cascades-table :data=$newDirectCascades />
            </div>
        @endif
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
                <livewire:site-indirect-cascades-table :data=$indirectCascades />
            </div>
        @endif
    </div>

    @if ($indirectCascades)
        <span class="indicator-item badge">@php
            echo count($indirectCascades);
        @endphp</span>
    @endif
</div>



{{-- </x-layouts.app> --}}
