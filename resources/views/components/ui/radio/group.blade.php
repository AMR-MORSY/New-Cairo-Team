@props(['label' => null, 'name' => null, 'error' => null, 'variant' => null])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $field = $name ?? (is_string($model) ? $model : null);
    $messages = $error !== null ? (array) $error : ($field ? $errors->get($field) : []);
@endphp

<div class="grid gap-2">
    @if ($label)
        <x-ui.label>{{ $label }}</x-ui.label>
    @endif
    <div {{ $attributes->class($variant === 'segmented' ? 'join' : 'flex flex-wrap gap-4') }}>
        {{ $slot }}
    </div>
    <x-ui.error :messages="$messages" />
</div>
