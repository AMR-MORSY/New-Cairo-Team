@props([
    'label' => null, 'description' => null, 'name' => null, 'error' => null,
    'placeholder' => null, 'size' => null, 'badge' => null,
])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $field = $name ?? (is_string($model) ? $model : null);
    $messages = $error !== null ? (array) $error : ($field ? $errors->get($field) : []);
    $id = $attributes->get('id') ?: 's-' . \Illuminate\Support\Str::slug((string) ($field ?? \Illuminate\Support\Str::random(8)), '-');
    $sizeClass = match ($size) { 'sm' => 'select-sm', 'lg' => 'select-lg', default => '' };
@endphp

<div class="grid gap-2">
    @if ($label)
        <x-ui.label :for="$id" :badge="$badge">{{ $label }}</x-ui.label>
    @endif

    <select id="{{ $id }}"
        {{ $attributes->except('id')->class(array_filter(['select w-full', $sizeClass, 'select-error' => (bool) $messages])) }}>
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @if ($description)
        <x-ui.description>{{ $description }}</x-ui.description>
    @endif
    <x-ui.error :messages="$messages" />
</div>
