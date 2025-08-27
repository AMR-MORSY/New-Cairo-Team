@props(['options'])
<flux:select {{ $attributes }}  class="w-full">
    @foreach ($options as $option)
        <flux:select.option :value="$option->id">{{ $option->name }}
        </flux:select.option>
    @endforeach

</flux:select>
