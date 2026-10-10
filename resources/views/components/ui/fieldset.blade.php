@props(['legend' => null])

<fieldset {{ $attributes->class('grid gap-4 rounded-box border border-base-300 p-4') }}>
    @if ($legend)
        <legend class="px-1 text-sm font-medium text-base-content/90">{{ $legend }}</legend>
    @endif
    {{ $slot }}
</fieldset>
