@props(['color' => null, 'size' => null])

@php
    $colorClass = match ($color) {
        'primary' => 'badge-primary', 'success', 'green' => 'badge-success',
        'warning', 'amber', 'yellow' => 'badge-warning',
        'danger', 'red' => 'badge-error', 'info', 'blue' => 'badge-info',
        default => 'badge-ghost',
    };
    $sizeClass = match ($size) { 'sm' => 'badge-sm', 'lg' => 'badge-lg', default => '' };
@endphp

<span {{ $attributes->class(trim("badge $colorClass $sizeClass")) }}>{{ $slot }}</span>
