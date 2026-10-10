@props(['size' => 'base', 'color' => null])

@php
    $sizeClass = match ($size) { 'sm' => 'text-sm', 'lg' => 'text-lg', default => 'text-base' };
    $colorClass = match ($color) {
        'green', 'success' => 'text-success',
        'red', 'danger' => 'text-error',
        'amber', 'yellow', 'warning' => 'text-warning',
        'blue', 'info' => 'text-info',
        default => 'text-base-content/80',
    };
@endphp

<p {{ $attributes->class("$sizeClass $colorClass") }}>{{ $slot }}</p>
