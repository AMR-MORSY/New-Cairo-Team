@props(['title', 'description' => null])

<div class="mb-8">
    <x-ui.heading size="2xl" :level="1">{{ $title }}</x-ui.heading>
    @if ($description)
        <x-ui.subheading class="mt-1.5">{{ $description }}</x-ui.subheading>
    @endif
</div>
