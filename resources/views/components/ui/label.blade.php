@props(['for' => null, 'badge' => null])

<label {{ $attributes->merge(['class' => 'label-text block text-sm font-medium text-base-content/90']) }}
    @if ($for) for="{{ $for }}" @endif>
    {{ $slot }}
    @if ($badge)
        <span class="badge badge-ghost badge-sm ms-1 align-middle">{{ $badge }}</span>
    @endif
</label>
