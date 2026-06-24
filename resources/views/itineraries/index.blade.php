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
                    @include('itineraries.partials.card', ['group' => $group, 'itinerary' => $itinerary])
                @endforeach
            </section>

            {{ $itineraries->links() }}
        @endif
    </div>
</x-app-layout>
