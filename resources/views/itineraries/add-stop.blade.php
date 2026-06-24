<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-sky">
                    <x-icon name="map-pin" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.itineraries.show', [$group, $itinerary]) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại lộ trình
                    </a>
                    <h1 class="section-title mt-2">Thêm điểm dừng</h1>
                    <p class="section-subtitle">{{ $itinerary->title }} · chọn địa điểm riêng của nhóm hoặc kho chung.</p>
                </div>
            </div>

            @can('manageDestinations', $group)
                <a href="{{ route('groups.destinations.create', $group) }}" class="action-secondary">
                    <x-icon name="plus" class="h-4 w-4" />
                    Thêm địa điểm riêng
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @include('groups.partials.nav', ['group' => $group])

        <section class="surface-panel p-4">
            <form method="GET" action="{{ route('groups.itineraries.stops.create', [$group, $itinerary]) }}" class="grid gap-3 md:grid-cols-[1fr_auto]">
                <div>
                    <label for="search" class="sr-only">Tìm địa điểm</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" class="field-control mt-0" placeholder="Tìm khách sạn, quán ăn, địa chỉ...">
                </div>
                <button type="submit" class="action-secondary">
                    <x-icon name="search" class="h-4 w-4" />
                    Lọc địa điểm
                </button>
            </form>
        </section>

        <form action="{{ route('groups.itineraries.add-location', [$group, $itinerary]) }}" method="POST" class="grid gap-6 xl:grid-cols-[0.66fr_0.34fr]">
            @csrf

            <section class="space-y-6">
                @error('destination_ref') <div class="alert-error">{{ $message }}</div> @enderror

                <div class="surface-panel p-5 sm:p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="icon-tile icon-tile-sky h-10 w-10">
                            <x-icon name="map-pin" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="card-title">Địa điểm riêng của nhóm</h2>
                            <p class="mt-1 text-sm text-stone-600">Dành cho khách sạn, điểm hẹn, quán quen hoặc nơi nhóm hay dùng.</p>
                        </div>
                    </div>

                    @if($groupLocations->isEmpty())
                        <div class="empty-state">
                            <p>Không có địa điểm riêng phù hợp.</p>
                            @can('manageDestinations', $group)
                                <a href="{{ route('groups.destinations.create', $group) }}" class="action-secondary mt-4">
                                    <x-icon name="plus" class="h-4 w-4" />
                                    Tạo địa điểm riêng
                                </a>
                            @endcan
                        </div>
                    @else
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach($groupLocations as $location)
                                <label class="block cursor-pointer rounded-lg border border-stone-200 bg-white p-4 hover:border-sky-300 has-[:checked]:border-sky-700 has-[:checked]:bg-sky-50">
                                    <input type="radio" name="destination_ref" value="group:{{ $location->id }}" class="sr-only" @checked(old('destination_ref') === 'group:'.$location->id)>
                                    <span class="badge">Riêng của nhóm</span>
                                    <span class="mt-3 block text-base font-semibold text-stone-950">{{ $location->name }}</span>
                                    <span class="mt-1 block text-sm leading-6 text-stone-600">{{ $location->address ?? 'Chưa có địa chỉ' }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="surface-panel p-5 sm:p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="map" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="card-title">Kho địa điểm chung</h2>
                            <p class="mt-1 text-sm text-stone-600">Các địa điểm dùng chung mà mọi người trong hệ thống có thể xem.</p>
                        </div>
                    </div>

                    @if($sharedLocations->isEmpty())
                        <div class="empty-state">Không có địa điểm chung phù hợp.</div>
                    @else
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach($sharedLocations as $location)
                                <label class="block cursor-pointer rounded-lg border border-stone-200 bg-white p-4 hover:border-emerald-300 has-[:checked]:border-emerald-700 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="destination_ref" value="shared:{{ $location->id }}" class="sr-only" @checked(old('destination_ref') === 'shared:'.$location->id)>
                                    <span class="badge">Kho chung</span>
                                    <span class="mt-3 block text-base font-semibold text-stone-950">{{ $location->name }}</span>
                                    <span class="mt-1 block text-sm leading-6 text-stone-600">{{ $location->address ?? 'Chưa có địa chỉ' }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <aside class="surface-panel h-fit p-5 sm:p-6 xl:sticky xl:top-24">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-amber h-10 w-10">
                        <x-icon name="clock" class="h-4 w-4" />
                    </span>
                    <h2 class="card-title">Thời gian và ghi chú</h2>
                </div>

                <div class="mt-5 space-y-5">
                    <div>
                        <label for="visit_time" class="label-quiet flex items-center gap-2">
                            <x-icon name="clock" class="h-4 w-4 text-emerald-800" />
                            Thời gian ghé thăm
                        </label>
                        <input type="datetime-local" name="visit_time" id="visit_time" value="{{ old('visit_time') }}" class="field-control">
                        @error('visit_time') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="note" class="label-quiet flex items-center gap-2">
                            <x-icon name="note" class="h-4 w-4 text-amber-800" />
                            Ghi chú / Hoạt động
                        </label>
                        <textarea name="note" id="note" rows="5" class="field-control" placeholder="Ví dụ: nhận phòng, ăn trưa, mua vé trước">{{ old('note') }}</textarea>
                        @error('note') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="action-primary w-full">
                        <x-icon name="plus" class="h-4 w-4" />
                        Thêm vào lộ trình
                    </button>
                </div>
            </aside>
        </form>
    </div>
</x-app-layout>
