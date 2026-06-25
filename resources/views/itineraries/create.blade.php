<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-emerald">
                <x-icon name="calendar" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('groups.itineraries.index', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại lịch trình
                </a>
                <h1 class="section-title mt-2">Tạo lịch trình</h1>
                <p class="section-subtitle">Lịch trình sẽ nằm trong nhóm {{ $group->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="narrow-shell space-y-6">
        @include('groups.partials.nav', ['group' => $group])

        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('groups.itineraries.store', $group) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="label-quiet flex items-center gap-2">
                        <x-icon name="route" class="h-4 w-4 text-emerald-800" />
                        Tên chuyến đi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="field-control" placeholder="Ví dụ: Hà Nội 3 ngày 2 đêm" required>
                    @error('title') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                @include('itineraries.partials.destination-select', [
                    'groupLocations' => $groupLocations,
                    'sharedLocations' => $sharedLocations,
                ])

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="label-quiet flex items-center gap-2">
                            <x-icon name="calendar" class="h-4 w-4 text-sky-800" />
                            Ngày bắt đầu <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="field-control" required>
                        @error('start_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="label-quiet flex items-center gap-2">
                            <x-icon name="flag" class="h-4 w-4 text-amber-800" />
                            Ngày kết thúc <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="field-control" required>
                        @error('end_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="label-quiet flex items-center gap-2">
                        <x-icon name="note" class="h-4 w-4 text-emerald-800" />
                        Ghi chú chuyến đi
                    </label>
                    <textarea name="description" id="description" rows="4" class="field-control" placeholder="Mục tiêu chuyến đi, chuẩn bị, hoặc ghi chú chung">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">
                        <x-icon name="plus" class="h-4 w-4" />
                        Tạo lịch trình
                    </button>
                    <a href="{{ route('groups.itineraries.index', $group) }}" class="action-secondary">
                        <x-icon name="x" class="h-4 w-4" />
                        Hủy
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
