@php
    $place = $place ?? null;
    $pickerId = $pickerId ?? 'destination-place-picker';
    $callbackName = 'initDestinationPlacePicker_' . preg_replace('/[^A-Za-z0-9_]/', '_', $pickerId);
    $latitude = old('latitude', $place?->latitude);
    $longitude = old('longitude', $place?->longitude);
    $title = $title ?? 'Chọn từ bản đồ';
    $description = $description ?? 'Tìm kiếm địa điểm hoặc bấm trên bản đồ để tự điền tên, địa chỉ và tọa độ.';
    $disabledMessage = $disabledMessage ?? 'Bản đồ chọn địa điểm đang tắt. Form bên dưới vẫn lưu thủ công bình thường.';
    $configuredProvider = $mapProvider ?? config('services.maps.provider', 'osm');
    $googleMapsKey = $googleMapsKey ?? config('services.google_maps.browser_key');
    $nominatimEndpoint = rtrim($nominatimEndpoint ?? config('services.maps.nominatim_endpoint', 'https://nominatim.openstreetmap.org'), '/');
    $useGoogleMaps = $configuredProvider === 'google' && filled($googleMapsKey);
    $useOpenStreetMap = ! $useGoogleMaps && $configuredProvider !== 'manual';
@endphp

<div class="rounded-lg border border-stone-200 bg-stone-50 p-4">
    <div class="flex items-start gap-3">
        <span class="icon-tile icon-tile-sky h-10 w-10">
            <x-icon name="map" class="h-4 w-4" />
        </span>
        <div>
            <h2 class="text-base font-semibold text-stone-950">{{ $title }}</h2>
            <p class="mt-1 text-sm leading-6 text-stone-600">{{ $description }}</p>
        </div>
    </div>

    @if($useGoogleMaps)
        <div class="mt-4">
            <label for="{{ $pickerId }}_search" class="label-quiet">Tìm địa điểm</label>
            <input type="text" id="{{ $pickerId }}_search" class="field-control" placeholder="Nhập tên quán, khách sạn, địa điểm...">
        </div>
        <div id="{{ $pickerId }}_map" class="mt-4 h-80 overflow-hidden rounded-md border border-stone-200 bg-stone-100"></div>
        <p class="mt-3 text-xs font-semibold text-stone-500">Có thể chỉnh lại tên hoặc địa chỉ sau khi chọn.</p>
    @elseif($useOpenStreetMap)
        @once
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        @endonce

        <div class="mt-4">
            <label for="{{ $pickerId }}_search" class="label-quiet">Tìm địa điểm</label>
            <div class="grid gap-2 sm:grid-cols-[1fr_auto]">
                <input type="text" id="{{ $pickerId }}_search" class="field-control" placeholder="Nhập tên quán, khách sạn, địa điểm...">
                <button type="button" id="{{ $pickerId }}_search_button" class="action-secondary mt-0">
                    <x-icon name="search" class="h-4 w-4" />
                    Tìm
                </button>
            </div>
            <div id="{{ $pickerId }}_results" class="mt-2 space-y-2"></div>
        </div>
        <div id="{{ $pickerId }}_map" class="mt-4 h-80 overflow-hidden rounded-md border border-stone-200 bg-stone-100"></div>
        <p class="mt-3 text-xs font-semibold text-stone-500">Demo dùng OpenStreetMap/Nominatim không cần key. Bấm bản đồ để lấy địa chỉ gần nhất.</p>
    @else
        <div class="mt-4 rounded-md border border-dashed border-amber-300 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
            {{ $disabledMessage }}
        </div>
    @endif

    <input type="hidden" name="place_provider" id="place_provider" value="{{ old('place_provider', $place?->place_provider) }}">
    <input type="hidden" name="place_id" id="place_id" value="{{ old('place_id', $place?->place_id) }}">
    <input type="hidden" name="latitude" id="latitude" value="{{ $latitude }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ $longitude }}">
</div>

@if($useGoogleMaps)
    <script>
        window.{{ $callbackName }} = function () {
            const mapElement = document.getElementById(@json($pickerId . '_map'));
            const searchInput = document.getElementById(@json($pickerId . '_search'));
            const nameInput = document.getElementById('name');
            const addressInput = document.getElementById('address');
            const placeProviderInput = document.getElementById('place_provider');
            const placeIdInput = document.getElementById('place_id');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            if (!mapElement || !searchInput || !nameInput || !addressInput || !placeProviderInput || !placeIdInput || !latitudeInput || !longitudeInput) {
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
                placeProviderInput.value = 'google';
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ urlencode($googleMapsKey) }}&libraries=places&callback={{ $callbackName }}" async defer></script>
@elseif($useOpenStreetMap)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapElement = document.getElementById(@json($pickerId . '_map'));
            const searchInput = document.getElementById(@json($pickerId . '_search'));
            const searchButton = document.getElementById(@json($pickerId . '_search_button'));
            const resultsElement = document.getElementById(@json($pickerId . '_results'));
            const nameInput = document.getElementById('name');
            const addressInput = document.getElementById('address');
            const placeProviderInput = document.getElementById('place_provider');
            const placeIdInput = document.getElementById('place_id');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');

            if (!mapElement || !searchInput || !searchButton || !resultsElement || !nameInput || !addressInput || !placeProviderInput || !placeIdInput || !latitudeInput || !longitudeInput) {
                return;
            }

            function initializeWhenReady() {
                if (typeof L === 'undefined') {
                    window.setTimeout(initializeWhenReady, 50);
                    return;
                }

                const initialLat = parseFloat(@json($latitude));
                const initialLng = parseFloat(@json($longitude));
                const hasInitialPoint = Number.isFinite(initialLat) && Number.isFinite(initialLng);
                const center = hasInitialPoint ? [initialLat, initialLng] : [16.047079, 108.206230];
                const map = L.map(mapElement, {
                    scrollWheelZoom: false,
                }).setView(center, hasInitialPoint ? 15 : 6);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(map);

                let marker = hasInitialPoint ? L.marker(center).addTo(map) : null;

                function setStatus(message, tone = 'stone') {
                    resultsElement.innerHTML = '';
                    const status = document.createElement('p');
                    status.className = tone === 'error'
                        ? 'rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-800'
                        : 'rounded-md border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600';
                    status.textContent = message;
                    resultsElement.appendChild(status);
                }

                function updateMarker(lat, lng) {
                    const nextPoint = [lat, lng];

                    if (!marker) {
                        marker = L.marker(nextPoint).addTo(map);
                    } else {
                        marker.setLatLng(nextPoint);
                    }

                    map.setView(nextPoint, 16);
                }

                function applySelection(selection) {
                    if (!Number.isFinite(selection.lat) || !Number.isFinite(selection.lng)) {
                        return;
                    }

                    updateMarker(selection.lat, selection.lng);
                    latitudeInput.value = selection.lat.toFixed(7);
                    longitudeInput.value = selection.lng.toFixed(7);
                    placeProviderInput.value = 'osm';
                    placeIdInput.value = selection.placeId || '';

                    if (selection.name) {
                        nameInput.value = selection.name;
                    }

                    if (selection.address) {
                        addressInput.value = selection.address;
                    }
                }

                function displayNameFor(place) {
                    return place.name || place.display_name?.split(',')[0] || '';
                }

                async function nominatim(path, params) {
                    const url = new URL(@json($nominatimEndpoint) + path);
                    Object.entries(params).forEach(([key, value]) => url.searchParams.set(key, value));
                    const response = await fetch(url.toString(), {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Nominatim request failed');
                    }

                    return response.json();
                }

                function renderResults(results) {
                    resultsElement.innerHTML = '';

                    if (!results.length) {
                        setStatus('Không tìm thấy địa điểm phù hợp.');
                        return;
                    }

                    results.forEach(function (result) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'block w-full rounded-md border border-stone-200 bg-white px-3 py-2 text-left text-sm leading-5 text-stone-700 hover:border-sky-300 hover:bg-sky-50';
                        button.textContent = result.display_name;
                        button.addEventListener('click', function () {
                            applySelection({
                                name: displayNameFor(result),
                                address: result.display_name,
                                placeId: result.place_id ? String(result.place_id) : '',
                                lat: parseFloat(result.lat),
                                lng: parseFloat(result.lon),
                            });
                            resultsElement.innerHTML = '';
                        });
                        resultsElement.appendChild(button);
                    });
                }

                async function searchPlaces() {
                    const query = searchInput.value.trim();

                    if (!query) {
                        setStatus('Nhập tên hoặc địa chỉ để tìm.');
                        return;
                    }

                    setStatus('Đang tìm địa điểm...');

                    try {
                        const results = await nominatim('/search', {
                            format: 'jsonv2',
                            addressdetails: '1',
                            limit: '5',
                            q: query,
                        });
                        renderResults(results);
                    } catch (error) {
                        setStatus('Không thể tìm địa điểm lúc này. Có thể nhập thủ công.', 'error');
                    }
                }

                searchButton.addEventListener('click', searchPlaces);
                searchInput.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchPlaces();
                    }
                });

                map.on('click', async function (event) {
                    const lat = event.latlng.lat;
                    const lng = event.latlng.lng;
                    updateMarker(lat, lng);
                    setStatus('Đang lấy địa chỉ gần điểm đã chọn...');

                    try {
                        const result = await nominatim('/reverse', {
                            format: 'jsonv2',
                            addressdetails: '1',
                            lat: String(lat),
                            lon: String(lng),
                        });
                        const formattedAddress = result.display_name || '';

                        applySelection({
                            name: displayNameFor(result) || nameInput.value,
                            address: formattedAddress,
                            placeId: result.place_id ? String(result.place_id) : '',
                            lat,
                            lng,
                        });
                        resultsElement.innerHTML = '';
                    } catch (error) {
                        applySelection({
                            name: nameInput.value,
                            address: addressInput.value,
                            placeId: '',
                            lat,
                            lng,
                        });
                        setStatus('Đã chọn tọa độ, nhưng chưa lấy được địa chỉ. Có thể nhập địa chỉ thủ công.', 'error');
                    }
                });
            }

            initializeWhenReady();
        });
    </script>
@endif
