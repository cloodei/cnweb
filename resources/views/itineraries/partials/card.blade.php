<article class="surface-panel flex flex-col justify-between p-6">
    <div>
        <div class="flex flex-wrap gap-2">
            <span class="badge-accent">
                <x-icon name="calendar" class="h-3.5 w-3.5" />
                {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
            </span>
            <span class="badge">
                <x-icon name="map-pin" class="h-3.5 w-3.5" />
                {{ $itinerary->scheduled_stops_count }} điểm dừng
            </span>
        </div>
        <h2 class="mt-4 text-lg font-semibold text-stone-950">{{ $itinerary->title }}</h2>
        <p class="mt-2 text-sm leading-6 text-stone-600">{{ $itinerary->description ?? 'Không có mô tả.' }}</p>
        <p class="mt-3 text-xs font-semibold text-stone-500">Người tạo: {{ $itinerary->creator->name ?? 'Người dùng đã bị xóa' }}</p>
    </div>

    <div class="mt-5 flex items-center justify-between border-t border-stone-200 pt-4">
        <a href="{{ route('groups.itineraries.show', [$group, $itinerary]) }}" class="link-quiet inline-flex items-center gap-1.5">
            Chi tiết
            <x-icon name="arrow-right" class="h-4 w-4" />
        </a>

        @can('delete', $itinerary)
            <form action="{{ route('groups.itineraries.destroy', [$group, $itinerary]) }}" method="POST" onsubmit="return confirm('Xóa lịch trình này khỏi nhóm?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-700 hover:text-red-900">
                    <x-icon name="trash" class="h-4 w-4" />
                    Xóa
                </button>
            </form>
        @endcan
    </div>
</article>