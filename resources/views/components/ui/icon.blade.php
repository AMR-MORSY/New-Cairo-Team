@props(['name' => null])

{{-- Flux supplied Heroicons; until that is replaced, an unknown name renders
     nothing rather than throwing on a missing component. --}}
@if ($name && view()->exists('components.icons.' . $name))
    <x-dynamic-component :component="'icons.' . $name" {{ $attributes->merge(['class' => 'size-4']) }} />
@endif
