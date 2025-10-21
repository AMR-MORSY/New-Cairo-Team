
<div class="flex items-start  max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
     
        {{ $links ?? '' }}
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading size="xl">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class=" w-4xl  lg:max-w-6xl ">
            {{ $slot }}
        </div>
    </div>
</div>
