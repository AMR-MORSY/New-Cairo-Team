@props(['size' => 'base'])

@php $sizeClass = $size === 'lg' ? 'text-base' : 'text-sm'; @endphp

<p {{ $attributes->class("$sizeClass text-base-content/60") }}>{{ $slot }}</p>
