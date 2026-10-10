
<div class="flex items-start  max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px] md:shrink-0">
     
        {{ $links ?? '' }}
    </div>

    <x-ui.separator class="md:hidden" />

    {{-- min-w-0: a flex item defaults to min-width:auto, so without this it
     widens to its content and any overflow-x-auto inside never engages,
     pushing the whole page sideways instead --}}
    <div class="min-w-0 flex-1 self-stretch max-md:pt-6">
        <x-ui.heading size="xl">{{ $heading ?? '' }}</x-ui.heading>
        <x-ui.subheading>{{ $subheading ?? '' }}</x-ui.subheading>

      
            {{ $slot }}
     
    </div>
</div>
