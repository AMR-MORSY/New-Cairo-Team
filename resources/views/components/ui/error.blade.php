@props(['name' => null, 'messages' => []])

@php
    $messages = $name
        ? $errors->get($name)
        : array_values(array_filter((array) $messages));
@endphp

@if ($messages)
    <p {{ $attributes->merge(['class' => 'text-sm text-error']) }}>{{ $messages[array_key_first($messages)] }}</p>
@endif
