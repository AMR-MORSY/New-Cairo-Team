@props(['label' => null, 'description' => null, 'name' => null, 'error' => null, 'rows' => 4, 'badge' => null])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $field = $name ?? (is_string($model) ? $model : null);
    $messages = $error !== null ? (array) $error : ($field ? $errors->get($field) : []);
    $id = $attributes->get('id') ?: 't-' . \Illuminate\Support\Str::slug((string) ($field ?? \Illuminate\Support\Str::random(8)), '-');
@endphp

<div class="grid gap-2">
    @if ($label)
        <x-ui.label :for="$id" :badge="$badge">{{ $label }}</x-ui.label>
    @endif

    <textarea id="{{ $id }}" rows="{{ $rows }}"
        {{ $attributes->except('id')->class(['textarea w-full', 'textarea-error' => (bool) $messages]) }}>{{ $slot }}</textarea>

    @if ($description)
        <x-ui.description>{{ $description }}</x-ui.description>
    @endif
    <x-ui.error :messages="$messages" />
</div>
