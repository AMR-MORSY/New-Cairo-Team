@props(['content' => null, 'position' => 'top'])

@php
    $positionClass = match ($position) {
        'bottom' => 'tooltip-bottom', 'left' => 'tooltip-left',
        'right' => 'tooltip-right', default => 'tooltip-top',
    };
@endphp

<div {{ $attributes->class("tooltip $positionClass") }} @if ($content) data-tip="{{ $content }}" @endif>
    {{ $slot }}
</div>
