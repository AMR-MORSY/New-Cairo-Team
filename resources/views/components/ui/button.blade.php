@props([
    'variant' => 'default',   // default | primary | filled | danger | ghost | subtle | outline
    'color' => null,          // Flux's second axis; only 'zinc' is used here
    'size' => null,           // xs | sm | lg
    'icon' => null,
    'href' => null,
    'square' => false,
    'block' => false,
    'loading' => null,        // a wire: action name - swaps the label for a spinner while it runs
])

@php
    // Flux spelled a neutral button as color="zinc"; daisyUI calls it btn-neutral
    $variantClass = $color === 'zinc' ? 'btn-neutral' : match ($variant) {
        'primary'          => 'btn-primary',
        'danger'           => 'btn-error',
        'filled'           => 'btn-neutral',
        'ghost', 'subtle'  => 'btn-ghost',
        'outline'          => 'btn-outline',
        default            => '',
    };

    $sizeClass = match ($size) {
        'xs' => 'btn-xs', 'sm' => 'btn-sm', 'lg' => 'btn-lg', default => '',
    };

    $classes = trim(implode(' ', array_filter([
        'btn', $variantClass, $sizeClass,
        $square ? 'btn-square' : '',
        $block ? 'btn-block' : '',
    ])));

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $attributes->get('type', 'button') }}" @endif
    @if ($loading) wire:target="{{ $loading }}" wire:loading.attr="disabled" @endif
    {{ $attributes->except('type')->class($classes) }}
>
    @if ($loading)
        {{-- display:contents so the swap does not disturb the btn flex row --}}
        <span class="contents" wire:loading.remove wire:target="{{ $loading }}">
            @if ($icon)
                <x-ui.icon :name="$icon" class="size-4" />
            @endif
            {{ $slot }}
        </span>
        <span class="loading loading-spinner loading-sm" wire:loading wire:target="{{ $loading }}"></span>
    @else
        @if ($icon)
            <x-ui.icon :name="$icon" class="size-4" />
        @endif
        {{ $slot }}
    @endif
</{{ $tag }}>
