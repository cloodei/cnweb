@php
    $itinerary = $itinerary ?? null;
    $selectedDestinationRef = old('destination_ref', $itinerary?->destinationRef());
@endphp

<div>
    <label for="destination_ref" class="label-quiet flex items-center gap-2">
        <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
        Điểm đến chính
    </label>
    <select name="destination_ref" id="destination_ref" class="field-control">
        <option value="">Chưa chọn điểm đến chính</option>

        @if($groupLocations->isNotEmpty())
            <optgroup label="Địa điểm riêng của nhóm">
                @foreach($groupLocations as $location)
                    <option value="group:{{ $location->id }}" @selected($selectedDestinationRef === 'group:'.$location->id)>
                        {{ $location->name }}{{ $location->address ? ' - '.$location->address : '' }}
                    </option>
                @endforeach
            </optgroup>
        @endif

        @if($sharedLocations->isNotEmpty())
            <optgroup label="Kho địa điểm chung">
                @foreach($sharedLocations as $location)
                    <option value="shared:{{ $location->id }}" @selected($selectedDestinationRef === 'shared:'.$location->id)>
                        {{ $location->name }}{{ $location->address ? ' - '.$location->address : '' }}
                    </option>
                @endforeach
            </optgroup>
        @endif
    </select>
    <p class="mt-2 text-xs font-medium text-stone-500">Tùy chọn. Địa điểm riêng của nhóm được ưu tiên để chọn nhanh, nhưng vẫn có thể dùng kho chung.</p>
    @error('destination_ref') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
</div>
