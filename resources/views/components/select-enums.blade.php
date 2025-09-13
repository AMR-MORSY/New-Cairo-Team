@props(['enumOptions'])
<flux:select {{ $attributes }}   class="w-full">
    @foreach ($enumOptions as $option)
        <flux:select.option :value="$option->value">{{ $option->name }}
        </flux:select.option>
    @endforeach

</flux:select>
