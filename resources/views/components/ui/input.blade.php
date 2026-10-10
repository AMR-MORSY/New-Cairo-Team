@props([
    'label' => null,
    'description' => null,
    'badge' => null,
    'error' => null,
    'name' => null,
    'type' => 'text',
    'size' => null,      // sm | lg
    'icon' => null,      // leading icon: an <x-icons.*> name
    'viewable' => false, // password reveal toggle
    'clearable' => false,
])

@php
    // Flux derived the error key from wire:model; keep that so callers need no change.
    $model = $attributes->whereStartsWith('wire:model')->first();
    $field = $name ?? (is_string($model) ? \Illuminate\Support\Str::before($model, '.') && $model : null);
    $field = $name ?? (is_string($model) ? $model : null);

    $messages = $error !== null ? (array) $error : ($field ? $errors->get($field) : []);
    $invalid = (bool) $messages;

    $id = $attributes->get('id') ?: 'f-' . \Illuminate\Support\Str::slug((string) ($field ?? \Illuminate\Support\Str::random(8)), '-');

    $boxed = $icon || $viewable || $clearable;

    $sizeClass = match ($size) { 'sm' => 'input-sm', 'lg' => 'input-lg', default => '' };
    $shell = trim("input w-full {$sizeClass} " . ($invalid ? 'input-error' : ''));

    // Classes the caller passes are meant for the control, not the wrapper.
    $control = $attributes->except(['type', 'id']);
@endphp

<div class="grid gap-2" @if ($viewable) x-data="{ show: false }" @endif>
    @if ($label)
        <x-ui.label :for="$id" :badge="$badge">{{ $label }}</x-ui.label>
    @endif

    @if ($boxed)
        <label class="{{ $shell }}">
            @if ($icon)
                <x-ui.icon :name="$icon" class="size-4 shrink-0 opacity-60" />
            @endif

            <input
                id="{{ $id }}"
                @if ($viewable) :type="show ? 'text' : '{{ $type }}'" @else type="{{ $type }}" @endif
                {{ $control->class('grow border-0 bg-transparent outline-none focus:outline-none') }}
            />

            @if ($viewable)
                <button type="button" tabindex="-1" class="cursor-pointer opacity-60 hover:opacity-100"
                    x-on:click="show = !show" :aria-label="show ? 'Hide' : 'Show'">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path x-show="!show" stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path x-show="!show" stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path x-show="show" x-cloak stroke-linecap="round" stroke-linejoin="round"
                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243" />
                    </svg>
                </button>
            @endif
        </label>
    @else
        <input id="{{ $id }}" type="{{ $type }}" {{ $control->class($shell) }} />
    @endif

    @if ($description)
        <x-ui.description>{{ $description }}</x-ui.description>
    @endif

    <x-ui.error :messages="$messages" />
</div>
