@props(['value' => null])

<option @if (! is_null($value)) value="{{ $value }}" @endif {{ $attributes }}>{{ $slot }}</option>
