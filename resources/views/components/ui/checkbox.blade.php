@props(['label' => null, 'description' => null, 'name' => null, 'error' => null])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $field = $name ?? (is_string($model) ? $model : null);
    $messages = $error !== null ? (array) $error : ($field ? $errors->get($field) : []);
    $id = $attributes->get('id') ?: 'c-' . \Illuminate\Support\Str::slug((string) ($field ?? \Illuminate\Support\Str::random(8)), '-');
@endphp

<div class="grid gap-1">
    <label class="flex cursor-pointer items-start gap-2.5" for="{{ $id }}">
        <input id="{{ $id }}" type="checkbox"
            {{ $attributes->except('id')->class(['checkbox checkbox-sm checkbox-primary mt-0.5', 'checkbox-error' => (bool) $messages]) }} />
        @if ($label || $slot->isNotEmpty())
            <span class="grid gap-0.5">
                <span class="text-sm text-base-content/90">{{ $label ?? $slot }}</span>
                @if ($description)
                    <span class="text-xs text-base-content/60">{{ $description }}</span>
                @endif
            </span>
        @endif
    </label>
    <x-ui.error :messages="$messages" />
</div>
