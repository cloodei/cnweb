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
                            @include('itineraries.partials.stop-item', ['group' => $group, 'itinerary' => $itinerary, 'stop' => $stop])
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
