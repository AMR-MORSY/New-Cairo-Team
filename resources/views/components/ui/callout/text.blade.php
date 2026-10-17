{{-- <span {{ $attributes->class('text-sm opacity-90') }}>{{ $slot }}</span> --}}
<span {{ $attributes->merge(['class' => 'text-sm opacity-90']) }}>{{ $slot }}</span>