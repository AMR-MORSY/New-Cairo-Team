@props(['variant' => null])

<ul {{ $attributes->class('menu w-full gap-0.5 p-0') }}>
    {{ $slot }}
</ul>
