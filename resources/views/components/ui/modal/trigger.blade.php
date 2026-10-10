@props(['name'])

<span {{ $attributes->class('contents') }}
    onclick="document.getElementById('modal-{{ $name }}')?.showModal()">
    {{ $slot }}
</span>
