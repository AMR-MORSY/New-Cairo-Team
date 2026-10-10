@props(['size' => 'base', 'level' => 2])

@php
    $sizeClass = match ($size) {
        'sm'  => 'text-sm',
        'lg'  => 'text-lg',
        'xl'  => 'text-xl',
        '2xl' => 'text-2xl',
        default => 'text-base',
    };
@endphp

<h{{ $level }} {{ $attributes->class("$sizeClass font-semibold text-base-content") }}>{{ $slot }}</h{{ $level }}>
