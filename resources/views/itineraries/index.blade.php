<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="route" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.show', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại tổng quan
                    </a>
                    <h1 class="section-title mt-2">Lịch trình</h1>
                    <p class="section-subtitle">{{ $group->name }} · Vai trò của bạn: {{ ucfirst($membershipRole) }}</p>
                </div>
            </div>

            @can('createItinerary', $group)
                <a href="{{ route('groups.itineraries.create', $group) }}" class="action-primary">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tạo lịch trình
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @include('groups.partials.nav', ['group' => $group])

        <section class="surface-panel p-4">
            <form method="GET" action="{{ route('groups.itineraries.index', $group) }}" class="grid gap-3 lg:grid-cols-[1fr_14rem_auto]">
                <div>
                    <label for="search" class="sr-only">Tìm lịch trình</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" class="field-control mt-0" placeholder="Tìm theo tên hoặc ghi chú">
                </div>
                <div>
                    <label for="status" class="sr-only">Trạng thái thời gian</label>
                    <select name="status" id="status" class="field-control mt-0">
                        <option value="">Tất cả thời gian</option>
                        <option value="active" @selected(request('status') === 'active')>Đang diễn ra</option>
                        <option value="upcoming" @selected(request('status') === 'upcoming')>Sắp tới</option>
                        <option value="past" @selected(request('status') === 'past')>Đã qua</option>
                    </select>
                </div>
                <button type="submit" class="action-secondary">
                    <x-icon name="search" class="h-4 w-4" />
                    Lọc
                </button>
            </form>
        </section>

        @if($itineraries->isEmpty())
            <section class="empty-state">
                <span class="icon-tile icon-tile-emerald mx-auto">
                    <x-icon name="calendar" class="h-5 w-5" />
                </span>
                <h2 class="mt-4 font-display text-2xl font-semibold text-stone-950">Chưa có lịch trình phù hợp.</h2>
                <p class="mt-2 text-sm text-stone-600">Tạo lịch trình mới hoặc thay đổi bộ lọc thời gian.</p>
                @can('createItinerary', $group)
                    <a href="{{ route('groups.itineraries.create', $group) }}" class="action-primary mt-5">
                        <x-icon name="plus" class="h-4 w-4" />
                        Tạo lịch trình
                    </a>
                @endcan
            </section>
        @else
            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($itineraries as $itinerary)
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
                            @if($itinerary->destinationName())
                                <p class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-sky-900">
                                    <x-icon name="map-pin" class="h-4 w-4" />
                                    {{ $itinerary->destinationName() }}
                                    <span class="badge">{{ $itinerary->destinationSourceLabel() }}</span>
                                </p>
                            @endif
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
                @endforeach
            </section>

            {{ $itineraries->links() }}
        @endif
    </div>
</x-app-layout>
