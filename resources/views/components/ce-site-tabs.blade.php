@props(['target'])

{{--
    The CE VIP / TP / G.SQR pages: the sites table, and beside it every site's
    live routes with the batteries along them. The Routes tab is lazy - its
    component only loads once the tab is shown, since it walks site_routes for
    every site.
--}}

{{--
    A flex column, not a grid: a grid's column grows to its widest content,
    so the wide sites table pushed the whole page sideways instead of
    scrolling inside its own box. A column's children are exactly as wide as
    the container, so overflow-x-auto below takes over.
--}}
<div x-data="{ tab: 'table' }" class="flex min-w-0 flex-col gap-4">
    <div role="tablist" class="tabs tabs-border">
        <button type="button" role="tab" class="tab" x-bind:class="{ 'tab-active': tab === 'table' }"
            x-on:click="tab = 'table'">{{ __('Table') }}</button>
        <button type="button" role="tab" class="tab" x-bind:class="{ 'tab-active': tab === 'routes' }"
            x-on:click="tab = 'routes'">{{ __('Routes') }}</button>
    </div>

    <div x-show="tab === 'table'" class="min-w-0 overflow-x-auto">
        <livewire:tables.site.nodal-sites-table :target="$target" />
    </div>

    <div x-show="tab === 'routes'" x-cloak class="min-w-0">
        <livewire:sites.actions.site-routes-with-batteries :target="$target" lazy />
    </div>
</div>
