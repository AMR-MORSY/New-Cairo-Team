@props(['enumOptions'])
<x-ui.select {{ $attributes }}   class="w-full">
    @foreach ($enumOptions as $option)
        <x-ui.select.option :value="$option->value">{{ $option->value }}
        </x-ui.select.option>
    @endforeach

</x-ui.select>
