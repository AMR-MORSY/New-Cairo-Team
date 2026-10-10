@props(['href' => '#', 'variant' => 'default'])

<a href="{{ $href }}"
    {{ $attributes->class([
        'link link-hover',
        'text-primary' => $variant === 'primary',
        'text-base-content/70 hover:text-base-content' => $variant === 'subtle',
    ]) }}>{{ $slot }}</a>
