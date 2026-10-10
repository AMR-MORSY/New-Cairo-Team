@props(['position' => 'bottom', 'align' => 'start'])

@php
    // daisyUI positions the panel with modifier classes; Flux used two props.
    $positionClass = match ($position) {
        'top' => 'dropdown-top', 'left' => 'dropdown-left',
        'right' => 'dropdown-right', default => 'dropdown-bottom',
    };
    $alignClass = match ($align) {
        'end' => 'dropdown-end', 'center' => 'dropdown-center', default => 'dropdown-start',
    };
@endphp

<div {{ $attributes->class("dropdown $positionClass $alignClass") }}>
    {{ $slot }}
</div>
