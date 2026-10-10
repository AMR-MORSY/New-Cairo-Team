@props(['vertical' => false, 'text' => null])

@if ($text)
    <div {{ $attributes->class('divider text-xs text-base-content/50') }}>{{ $text }}</div>
@else
    <div {{ $attributes->class($vertical ? 'divider divider-horizontal' : 'divider my-0') }}></div>
@endif
