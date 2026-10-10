@props(['status'])

@if ($status)
    <x-ui.callout variant="success" {{ $attributes->class('mb-6') }}>{{ $status }}</x-ui.callout>
@endif
