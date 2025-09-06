@props(['selected','row'])
<div>
    <select wire:change="scopeChanged($event.target.value,$row)">
            @foreach ($scopes as $scope)
                <option
                    value="{{ $scope }}"
                    @if ($scope  == $selected)
                        selected="selected"
                    @endif
                >
                    {{ $scope }}
                </option>
            @endforeach
    </select>
</div>