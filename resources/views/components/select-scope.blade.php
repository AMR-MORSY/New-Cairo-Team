@props(['selected'])
<div>
    <select wire:change="scopeChanged($event.target.value)" wire:model="selectedScope">

        @foreach ($scopes as $scope)
            <option value="{{ $scope }}" >
                {{ $scope }}
            </option>
        @endforeach
    </select>
</div>
