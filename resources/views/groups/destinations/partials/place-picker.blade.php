@php
    $location = $groupLocation ?? null;
    $latitude = old('latitude', $location?->latitude);
    $longitude = old('longitude', $location?->longitude);
@endphp

<div class="rounded-lg border border-stone-200 bg-stone-50 p-4">
    <div class="flex items-start gap-3">
        <span class="icon-tile icon-tile-sky h-10 w-10">
            <x-icon name="map" class="h-4 w-4" />
        </span>
        <div>
            <h2 class="text-base font-semibold text-stone-950">Chọn từ Google Maps</h2>
            <p class="mt-1 text-sm leading-6 text-stone-600">Tìm kiếm địa điểm hoặc bấm trên bản đồ để tự điền tên, địa chỉ và tọa độ.</p>
        </div>
    </div>

    @if($googleMapsKey)
        <div class="mt-4">
            <label for="place_search" class="label-quiet">Tìm địa điểm</label>
            <input type="text" id="place_search" class="field-control" placeholder="Nhập tên quán, khách sạn, địa điểm...">
        </div>
        <div id="group-location-map" class="mt-4 h-80 overflow-hidden rounded-md border border-stone-200 bg-stone-100"></div>
        <p class="mt-3 text-xs font-semibold text-stone-500">Có thể chỉnh lại tên hoặc địa chỉ sau khi chọn.</p>
    @else
        <div class="mt-4 rounded-md border border-dashed border-amber-300 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
            Chưa cấu hình `GOOGLE_MAPS_BROWSER_KEY`, nên bản đồ chọn địa điểm đang tắt. Form bên dưới vẫn lưu thủ công bình thường.
        </div>
    @endif

    <input type="hidden" name="google_place_id" id="google_place_id" value="{{ old('google_place_id', $location?->google_place_id) }}">
    <input type="hidden" name="latitude" id="latitude" value="{{ $latitude }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ $longitude }}">
</div>

@if($googleMapsKey)
    <script>
        window.initGroupLocationPicker = function () {
            const mapElement = document.getElementById('group-location-map');
            const searchInput = document.getElementById('place_search');
            const nameInput = document.getElementById('name');
            const addressInput = document.getElementById('address');
            const placeIdInput = document.getElementById('google_place_id');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            if (!mapElement || !searchInput || !nameInput || !addressInput) {
                return;
            }

            const initialLat = parseFloat(@json($latitude));
            const initialLng = parseFloat(@json($longitude));
            const hasInitialPoint = Number.isFinite(initialLat) && Number.isFinite(initialLng);
            const center = hasInitialPoint
                ? { lat: initialLat, lng: initialLng }
                : { lat: 16.047079, lng: 108.206230 };
            const map = new google.maps.Map(mapElement, {
                center,
                zoom: hasInitialPoint ? 15 : 6,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });
            const marker = new google.maps.Marker({
                map,
                position: hasInitialPoint ? center : null,
            });
            const geocoder = new google.maps.Geocoder();
            const autocomplete = new google.maps.places.Autocomplete(searchInput, {
                fields: ['formatted_address', 'geometry', 'name', 'place_id'],
            });

            function applySelection(selection) {
                if (!Number.isFinite(selection.lat) || !Number.isFinite(selection.lng)) {
                    return;
                }

                const nextPosition = { lat: selection.lat, lng: selection.lng };
                marker.setPosition(nextPosition);
                map.panTo(nextPosition);
                map.setZoom(16);
                latitudeInput.value = selection.lat.toFixed(7);
                longitudeInput.value = selection.lng.toFixed(7);
                placeIdInput.value = selection.placeId || '';

                if (selection.name) {
                    nameInput.value = selection.name;
                }

                if (selection.address) {
                    addressInput.value = selection.address;
                }
            }

            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    return;
                }

                applySelection({
                    name: place.name,
                    address: place.formatted_address,
                    placeId: place.place_id,
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                });
            });

            map.addListener('click', function (event) {
                const clickedPosition = event.latLng;

                geocoder.geocode({ location: clickedPosition }, function (results, status) {
                    const result = status === 'OK' && results[0] ? results[0] : null;
                    const formattedAddress = result?.formatted_address || '';

                    applySelection({
                        name: formattedAddress ? formattedAddress.split(',')[0] : nameInput.value,
                        address: formattedAddress,
                        placeId: result?.place_id,
                        lat: clickedPosition.lat(),
                        lng: clickedPosition.lng(),
                    });
                });
            });
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ urlencode($googleMapsKey) }}&libraries=places&callback=initGroupLocationPicker" async defer></script>
@endif
