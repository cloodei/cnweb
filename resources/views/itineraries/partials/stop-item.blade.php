<article class="timeline-card">
    <div class="flex flex-col gap-4 sm:flex-row">
        @if($stop->location?->image)
            <img src="{{ asset('storage/' . $stop->location->image) }}" alt="{{ $stop->destinationName() }}" class="h-32 w-full rounded-md border border-stone-200 object-cover sm:w-40">
        @else
            <div class="media-placeholder h-32 w-full sm:w-40">
                <x-icon name="map-pin" class="h-6 w-6" />
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-semibold text-stone-950">{{ $stop->destinationName() }}</h3>
                        <span class="badge">{{ $stop->sourceLabel() }}</span>
                    </div>
                    <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-stone-600">
                        <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                        {{ $stop->destinationAddress() ?? 'Chưa cập nhật địa chỉ' }}
                    </p>
                </div>

                @can('manageStops', $itinerary)
                    <form action="{{ route('groups.itineraries.remove-stop', ['group' => $group->id, 'itinerary' => $itinerary->id, 'stop' => $stop->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ địa điểm này khỏi chuyến đi?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-700 hover:text-red-900">
                            <x-icon name="trash" class="h-4 w-4" />
                            Gỡ
                        </button>
                    </form>
                @endcan
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                @if($stop->visit_time)
                    <span class="badge-accent">
                        <x-icon name="clock" class="h-3.5 w-3.5" />
                        {{ $stop->visit_time->format('d/m/Y H:i') }}
                    </span>
                @else
                    <span class="badge">
                        <x-icon name="clock" class="h-3.5 w-3.5" />
                        Chưa xếp giờ
                    </span>
                @endif
            </div>

            @if($stop->note)
                <div class="mt-4 rounded-md border border-dashed border-stone-300 bg-white p-3">
                    <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500">
                        <x-icon name="note" class="h-3.5 w-3.5" />
                        Ghi chú
                    </p>
                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-stone-700">{{ $stop->note }}</p>
                </div>
            @endif

            @if($stop->destinationMapQuery())
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($stop->destinationMapQuery()) }}" target="_blank" class="link-quiet mt-4 inline-flex items-center gap-1.5 text-sm">
                    Mở trên Google Maps
                    <x-icon name="arrow-right" class="h-4 w-4" />
                </a>
            @endif
        </div>
    </div>
</article>