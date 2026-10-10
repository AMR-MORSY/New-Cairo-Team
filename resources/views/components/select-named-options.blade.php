@props([
    'options',
    'selected' => null,
    // WireUI's select named these; callers pass the keys to read off each option
    'optionLabel' => 'name',
    'optionValue' => 'id',
])

@php
    /*
     * Normalise whatever the caller hands us to plain id/name pairs: Eloquent
     * collections (subcontractors, projects, requesters, actions) and arrays
     * already shaped that way (ModificationStatus::options()) both work.
     */
    $items = collect($options)
        ->map(fn ($option) => [
            'id' => data_get($option, $optionValue),
            'name' => data_get($option, $optionLabel),
        ])
        ->filter(fn (array $option) => $option['id'] !== null)
        ->values()
        ->all();

    /*
     * The daisyUI x-ui.select takes its <option> tags as a slot - it has no
     * :options prop, so an options array left in the attribute bag ends up
     * being rendered as an HTML attribute and trips ComponentAttributeBag
     * ("trim(): Argument #1 must be of type string, array given").
     *
     * WireUI's "multiselect" was a tag picker. A native <select multiple> is
     * not a drop-in replacement: .select is styled as a single-line control,
     * so the options pile up on one row, and it would still demand ctrl-click.
     * A checkbox list reads the same wire:model array without either problem.
     */
    $multiple = $attributes->has('multiselect') || $attributes->has('multiple');

    $selectedValues = array_map(
        'strval',
        array_filter((array) $selected, fn ($value) => $value !== null && $value !== '')
    );

    $isSelected = fn ($id) => in_array((string) $id, $selectedValues, true);
@endphp

@if ($multiple)
    @php
        $model = $attributes->whereStartsWith('wire:model')->first();
        $field = is_string($model) ? $model : null;
        $messages = $field ? $errors->get($field) : [];
        $idPrefix = 'ms-' . \Illuminate\Support\Str::slug((string) ($field ?? \Illuminate\Support\Str::random(8)), '-');
    @endphp

    <div class="grid gap-2">
        @if ($attributes->get('placeholder'))
            <x-ui.description>{{ $attributes->get('placeholder') }}</x-ui.description>
        @endif

        <div @class([
            'max-h-56 overflow-y-auto rounded-box border border-base-300 bg-base-100 p-3',
            'border-error' => (bool) $messages,
        ])>
            <div class="grid gap-2.5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($items as $item)
                    <x-ui.checkbox {{ $attributes->whereStartsWith('wire:model') }} :id="$idPrefix . '-' . $item['id']"
                        :value="$item['id']" :checked="$isSelected($item['id'])" :error="[]"
                        :label="$item['name']" />
                @endforeach
            </div>
        </div>
        <x-ui.error :messages="$messages" />
    </div>
@else
    {{-- the spread has to be written as $attributes->...; Blade's component-tag
         compiler only matches that literal name, a precomputed bag is ignored --}}
    <x-ui.select {{ $attributes->except(['multiselect', 'multiple', 'option-label', 'option-value']) }}>
        @foreach ($items as $item)
            <x-ui.select.option :value="$item['id']"
                :selected="$isSelected($item['id'])">{{ $item['name'] }}</x-ui.select.option>
        @endforeach
    </x-ui.select>
@endif
