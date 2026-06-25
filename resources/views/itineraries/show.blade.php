<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="route" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.itineraries.index', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại lịch trình
                    </a>
                    <h1 class="section-title mt-2">{{ $itinerary->title }}</h1>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="badge-accent">
                            <x-icon name="calendar" class="h-3.5 w-3.5" />
                            {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                        </span>
                        <span class="badge">
                            <x-icon name="users" class="h-3.5 w-3.5" />
                            Vai trò: {{ ucfirst($membershipRole) }}
                        </span>
                        @if($itinerary->destinationName())
                            <span class="badge">
                                <x-icon name="map-pin" class="h-3.5 w-3.5" />
                                {{ $itinerary->destinationName() }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid gap-2 sm:flex sm:flex-wrap sm:justify-end">
                @can('update', $itinerary)
                    <a href="{{ route('groups.itineraries.edit', [$group, $itinerary]) }}" class="action-secondary">
                        <x-icon name="pencil" class="h-4 w-4" />
                        Sửa thông tin
                    </a>
                @endcan
                @can('downloadPdf', $itinerary)
                    <a href="{{ route('groups.itineraries.pdf', [$group, $itinerary]) }}" class="action-secondary">
                        <x-icon name="file-text" class="h-4 w-4" />
                        Tải PDF
                    </a>
                @endcan
                @can('manageStops', $itinerary)
                    <a href="{{ route('groups.itineraries.stops.create', [$group, $itinerary]) }}" class="action-primary">
                        <x-icon name="plus" class="h-4 w-4" />
                        Thêm điểm dừng
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @include('groups.partials.nav', ['group' => $group])

        @if($itinerary->destinationName())
            <section class="surface-panel p-5 sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="icon-tile icon-tile-sky h-10 w-10">
                            <x-icon name="map-pin" class="h-4 w-4" />
                        </span>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="card-title">{{ $itinerary->destinationName() }}</h2>
                                <span class="badge">{{ $itinerary->destinationSourceLabel() }}</span>
                            </div>
                            <p class="mt-1 text-sm leading-6 text-stone-600">{{ $itinerary->destinationAddress() ?? 'Chưa cập nhật địa chỉ' }}</p>
                        </div>
                    </div>

                    @if($itinerary->destinationMapUrl())
                        <a href="{{ $itinerary->destinationMapUrl() }}" target="_blank" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                            Mở bản đồ
                            <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </section>
        @endif

        @if($itinerary->description)
            <section class="surface-panel p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-amber h-10 w-10">
                        <x-icon name="note" class="h-4 w-4" />
                    </span>
                    <h2 class="card-title">Ghi chú chuyến đi</h2>
                </div>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $itinerary->description }}</p>
            </section>
        @endif

        <div class="grid gap-6">
            <section class="surface-panel p-5 sm:p-6">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="route" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="card-title">Lộ trình chuyến đi</h2>
                            <p class="mt-1 text-sm text-stone-600">{{ $scheduledStops->count() }} điểm dừng đã lên lịch.</p>
                        </div>
                    </div>
                </div>

                @if($scheduledStops->isEmpty())
                    <div class="empty-state">
                        <span class="icon-tile icon-tile-sky mx-auto">
                            <x-icon name="map-pin" class="h-5 w-5" />
                        </span>
                        <p class="mt-4">Chuyến đi này chưa có điểm dừng nào.</p>
                        @can('manageStops', $itinerary)
                            <a href="{{ route('groups.itineraries.stops.create', [$group, $itinerary]) }}" class="action-secondary mt-4">
                                <x-icon name="plus" class="h-4 w-4" />
                                Thêm điểm dừng đầu tiên
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($scheduledStops as $stop)
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

                                        @if($stop->destinationMapUrl())
                                            <a href="{{ $stop->destinationMapUrl() }}" target="_blank" class="link-quiet mt-4 inline-flex items-center gap-1.5 text-sm">
                                                Mở bản đồ
                                                <x-icon name="arrow-right" class="h-4 w-4" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
