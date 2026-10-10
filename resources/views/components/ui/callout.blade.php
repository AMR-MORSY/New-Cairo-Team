@props([
    'variant' => 'secondary',
    'icon' => null,
    'heading' => null,
    'inline' => false,
    'actions' => null,
])

@php
    $variantClass = match ($variant) {
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'danger', 'error' => 'alert-error',
        'info' => 'alert-info',
        default => '',
    };
@endphp

<div role="alert"
    {{ $attributes->class(trim("alert alert-soft items-start $variantClass")) }}>
    @if ($icon)
        <x-ui.icon :name="$icon" class="size-5 shrink-0" />
    @endif

    <div class="grid min-w-0 gap-1">
        @if ($heading)
            <span class="font-semibold">{{ $heading }}</span>
        @endif
        {{ $slot }}
    </div>

    {{-- Flux put buttons in a trailing "actions" slot; keep that contract --}}
    @isset($actions)
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>
