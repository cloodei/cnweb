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
                        Quay lại {{ $group->name }}
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
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
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

        <div class="grid gap-6 lg:grid-cols-[0.38fr_0.62fr]">
            @can('manageStops', $itinerary)
                <aside class="surface-panel h-fit p-5 sm:p-6 lg:sticky lg:top-24">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-sky h-10 w-10">
                            <x-icon name="plus" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Thêm điểm dừng</h2>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Chọn địa điểm từ kho chung rồi thêm thời gian và ghi chú riêng cho lịch trình này.</p>

                    <form action="{{ route('groups.itineraries.add-location', [$group, $itinerary]) }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        <div>
                            <label for="location_id" class="label-quiet flex items-center gap-2">
                                <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                                Địa điểm
                            </label>
                            <select name="location_id" id="location_id" class="field-control" required>
                                <option value="">Chọn địa điểm</option>
                                @foreach($allLocations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                        {{ $loc->name }}{{ $loc->address ? ' - '.$loc->address : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                        </div>

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
                            <textarea name="note" id="note" rows="4" class="field-control" placeholder="Ví dụ: ăn trưa, chụp ảnh, mua vé trước">{{ old('note') }}</textarea>
                            @error('note') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="action-primary w-full">
                            <x-icon name="plus" class="h-4 w-4" />
                            Thêm vào lộ trình
                        </button>
                    </form>
                </aside>
            @else
                <aside class="surface-panel h-fit p-5 sm:p-6 lg:sticky lg:top-24">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-sky h-10 w-10">
                            <x-icon name="lock" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Chỉ xem</h2>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Bạn có thể xem lịch trình và xuất PDF, nhưng không thể thay đổi điểm dừng.</p>
                </aside>
            @endcan

            <section class="surface-panel p-5 sm:p-6">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="route" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="card-title">Lộ trình chuyến đi</h2>
                            <p class="mt-1 text-sm text-stone-600">{{ $scheduledLocations->count() }} điểm dừng đã lên lịch.</p>
                        </div>
                    </div>
                </div>

                @if($scheduledLocations->isEmpty())
                    <div class="empty-state">
                        <span class="icon-tile icon-tile-sky mx-auto">
                            <x-icon name="map-pin" class="h-5 w-5" />
                        </span>
                        <p class="mt-4">Chuyến đi này chưa có điểm dừng nào.</p>
                        <p class="mt-2">Sử dụng form bên cạnh để bắt đầu xếp lịch.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($scheduledLocations as $sl)
                            <article class="timeline-card">
                                <div class="flex flex-col gap-4 sm:flex-row">
                                    @if($sl->image)
                                        <img src="{{ asset('storage/' . $sl->image) }}" alt="{{ $sl->name }}" class="h-32 w-full rounded-md border border-stone-200 object-cover sm:w-40">
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-stone-950">{{ $sl->name }}</h3>
                                                <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-stone-600">
                                                    <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                                                    {{ $sl->address ?? 'Chưa cập nhật địa chỉ' }}
                                                </p>
                                            </div>

                                            @can('manageStops', $itinerary)
                                                <form action="{{ route('groups.itineraries.remove-stop', ['group' => $group->id, 'itinerary' => $itinerary->id, 'stop' => $sl->pivot->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ địa điểm này khỏi chuyến đi?');">
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
                                            @if($sl->pivot->visit_time)
                                                <span class="badge-accent">
                                                    <x-icon name="clock" class="h-3.5 w-3.5" />
                                                    {{ date('d/m/Y H:i', strtotime($sl->pivot->visit_time)) }}
                                                </span>
                                            @else
                                                <span class="badge">
                                                    <x-icon name="clock" class="h-3.5 w-3.5" />
                                                    Chưa xếp giờ
                                                </span>
                                            @endif
                                        </div>

                                        @if($sl->pivot->note)
                                            <div class="mt-4 rounded-md border border-dashed border-stone-300 bg-white p-3">
                                                <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500">
                                                    <x-icon name="note" class="h-3.5 w-3.5" />
                                                    Ghi chú
                                                </p>
                                                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-stone-700">{{ $sl->pivot->note }}</p>
                                            </div>
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
