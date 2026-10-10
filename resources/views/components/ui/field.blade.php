{{-- Vertical label / control / error stack. Mirrors the old Flux field wrapper. --}}
<div {{ $attributes->merge(['class' => 'grid gap-2']) }}>
    {{ $slot }}
</div>
