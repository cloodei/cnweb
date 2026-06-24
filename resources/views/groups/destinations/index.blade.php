<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-sky">
                    <x-icon name="map-pin" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.show', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại tổng quan
                    </a>
                    <h1 class="section-title mt-2">Địa điểm riêng</h1>
                    <p class="section-subtitle">{{ $group->name }} · Lưu các điểm đến riêng, khách sạn,quán ăn cụ thể để chọn nhanh khi xếp lịch trình.</p>
                </div>
            </div>

            @can('manageDestinations', $group)
                <a href="{{ route('groups.destinations.create', $group) }}" class="action-primary">
                    <x-icon name="plus" class="h-4 w-4" />
                    Thêm địa điểm riêng
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
            <form method="GET" action="{{ route('groups.destinations.index', $group) }}" class="grid gap-3 md:grid-cols-[1fr_auto]">
                <div>
                    <label for="search" class="sr-only">Tìm địa điểm riêng</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" class="field-control mt-0" placeholder="Tìm theo tên hoặc địa chỉ">
                </div>
                <button type="submit" class="action-secondary">
                    <x-icon name="search" class="h-4 w-4" />
                    Lọc
                </button>
            </form>
        </section>

        @if($locations->isEmpty())
            <section class="empty-state">
                <span class="icon-tile icon-tile-sky mx-auto">
                    <x-icon name="map-pin" class="h-5 w-5" />
                </span>
                <h2 class="mt-4 font-display text-2xl font-semibold text-stone-950">Chưa có địa điểm riêng.</h2>
                <p class="mt-2 text-sm text-stone-600">Thêm khách sạn, điểm hẹn, quán quen hoặc bất kỳ nơi nào nhóm muốn chọn nhanh.</p>
                @can('manageDestinations', $group)
                    <a href="{{ route('groups.destinations.create', $group) }}" class="action-primary mt-5">
                        <x-icon name="plus" class="h-4 w-4" />
                        Thêm địa điểm riêng
                    </a>
                @endcan
            </section>
        @else
            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($locations as $location)
                    <article class="surface-panel flex flex-col justify-between p-5">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="icon-tile icon-tile-sky h-10 w-10">
                                    <x-icon name="map-pin" class="h-4 w-4" />
                                </span>
                                <span class="badge">Riêng của nhóm</span>
                            </div>
                            <h2 class="mt-4 text-lg font-semibold text-stone-950">{{ $location->name }}</h2>
                            <p class="mt-2 text-sm leading-6 text-stone-600">{{ $location->address ?? 'Chưa có địa chỉ.' }}</p>
                            @if($location->description)
                                <p class="mt-3 text-sm leading-6 text-stone-600">{{ $location->description }}</p>
                            @endif
                            @if($location->latitude !== null && $location->longitude !== null)
                                <p class="mt-3 font-mono text-xs text-stone-500">{{ $location->latitude }}, {{ $location->longitude }}</p>
                            @endif
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-stone-200 pt-4">
                            @php($mapQuery = $location->mapSearchQuery())
                            @if($mapQuery)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location->mapSearchQuery()) }}" target="_blank" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                                    Mở Maps
                                    <x-icon name="arrow-right" class="h-4 w-4" />
                                </a>
                            @else
                                <span class="text-sm text-stone-400">Chưa có vị trí</span>
                            @endif

                            @can('manageDestinations', $group)
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('groups.destinations.edit', [$group, $location]) }}" class="text-sm font-semibold text-stone-700 hover:text-stone-950">Sửa</a>
                                    <form action="{{ route('groups.destinations.destroy', [$group, $location]) }}" method="POST" onsubmit="return confirm('Xóa địa điểm riêng này khỏi nhóm?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-700 hover:text-red-900">Xóa</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </article>
                @endforeach
            </section>

            {{ $locations->links() }}
        @endif
    </div>
</x-app-layout>
