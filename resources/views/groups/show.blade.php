<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="users" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.index') }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại nhóm
                    </a>
                    <h1 class="section-title mt-2">{{ $group->name }}</h1>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="badge-accent">
                            <x-icon name="users" class="h-3.5 w-3.5" />
                            {{ $memberCount }} thành viên
                        </span>
                        <span class="badge">
                            <x-icon name="map-pin" class="h-3.5 w-3.5" />
                            {{ $destinationCount }} địa điểm nhóm
                        </span>
                        <span class="badge">
                            Vai trò: {{ ucfirst($membershipRole) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid gap-2 sm:flex sm:flex-wrap sm:justify-end">
                @can('update', $group)
                    <a href="{{ route('groups.edit', $group) }}" class="action-secondary">
                        <x-icon name="pencil" class="h-4 w-4" />
                        Sửa nhóm
                    </a>
                @endcan
                @can('createItinerary', $group)
                    <a href="{{ route('groups.itineraries.create', $group) }}" class="action-primary">
                        <x-icon name="plus" class="h-4 w-4" />
                        Tạo lịch trình
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @include('groups.partials.nav', ['group' => $group])

        <section class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('groups.destinations.index', $group) }}" class="stat-card hover:border-sky-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Địa điểm nhóm</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $destinationCount }}</p>
                    </div>
                    <span class="icon-tile icon-tile-sky">
                        <x-icon name="map-pin" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Nơi nhóm lưu các điểm hay dùng mà không đưa vào kho chung.</p>
            </a>

            <a href="{{ route('groups.itineraries.index', $group) }}" class="stat-card hover:border-emerald-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Lịch trình</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $itineraryCount }}</p>
                    </div>
                    <span class="icon-tile icon-tile-emerald">
                        <x-icon name="route" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Kế hoạch chuyến đi trong nhóm, có lọc theo thời gian.</p>
            </a>

            <a href="{{ route('groups.members', $group) }}" class="stat-card hover:border-amber-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Thành viên</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $memberCount }}</p>
                    </div>
                    <span class="icon-tile icon-tile-amber">
                        <x-icon name="mail" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Quản lý thành viên và link mời có thời hạn.</p>
            </a>
        </section>

        <div class="grid gap-6 xl:grid-cols-[0.44fr_0.56fr]">
            <section class="surface-panel p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-amber h-10 w-10">
                        <x-icon name="note" class="h-4 w-4" />
                    </span>
                    <h2 class="card-title">Ghi chú nhóm</h2>
                </div>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $group->description ?? 'Chưa có mô tả nhóm.' }}</p>

                @if($nextItinerary)
                    <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-900">Lịch trình sắp tới</p>
                        <h3 class="mt-2 text-base font-semibold text-stone-950">{{ $nextItinerary->title }}</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            {{ date('d/m/Y', strtotime($nextItinerary->start_date)) }} - {{ date('d/m/Y', strtotime($nextItinerary->end_date)) }}
                        </p>
                        @if($nextItinerary->destinationName())
                            <p class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-950">
                                <x-icon name="map-pin" class="h-4 w-4" />
                                {{ $nextItinerary->destinationName() }}
                            </p>
                        @endif
                        <a href="{{ route('groups.itineraries.show', [$group, $nextItinerary]) }}" class="link-quiet mt-3 inline-flex items-center gap-1.5 text-sm">
                            Mở lịch trình
                            <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                @endif
            </section>

            <section class="surface-panel p-5 sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="route" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Lịch trình gần nhất</h2>
                    </div>
                    <a href="{{ route('groups.itineraries.index', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        Xem tất cả
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>

                @if($itineraries->isEmpty())
                    <div class="empty-state">
                        <p>Nhóm này chưa có lịch trình nào.</p>
                        @can('createItinerary', $group)
                            <a href="{{ route('groups.itineraries.create', $group) }}" class="action-primary mt-5">
                                <x-icon name="plus" class="h-4 w-4" />
                                Tạo lịch trình đầu tiên
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($itineraries as $itinerary)
                            <a href="{{ route('groups.itineraries.show', [$group, $itinerary]) }}" class="block rounded-lg border border-stone-200 bg-white p-4 hover:border-emerald-300 hover:bg-emerald-50/40">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h3 class="text-base font-semibold text-stone-950">{{ $itinerary->title }}</h3>
                                        <p class="mt-1 text-sm text-stone-600">{{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}</p>
                                        @if($itinerary->destinationName())
                                            <p class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-sky-900">
                                                <x-icon name="map-pin" class="h-4 w-4" />
                                                {{ $itinerary->destinationName() }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="badge">
                                        <x-icon name="map-pin" class="h-3.5 w-3.5" />
                                        {{ $itinerary->scheduled_stops_count }} điểm dừng
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
