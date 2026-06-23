<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-amber">
                <x-icon name="pencil" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('itineraries.show', $itinerary) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại chi tiết
                </a>
                <h1 class="section-title mt-2">Sửa lịch trình</h1>
                <p class="section-subtitle">{{ $itinerary->title }}</p>
            </div>
        </div>
    </x-slot>

    <div class="narrow-shell">
        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('itineraries.update', $itinerary) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="label-quiet flex items-center gap-2">
                        <x-icon name="route" class="h-4 w-4 text-emerald-800" />
                        Tên chuyến đi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $itinerary->title) }}" class="field-control" required>
                    @error('title') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="label-quiet flex items-center gap-2">
                            <x-icon name="calendar" class="h-4 w-4 text-sky-800" />
                            Ngày bắt đầu <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d', strtotime($itinerary->start_date))) }}" class="field-control" required>
                        @error('start_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="label-quiet flex items-center gap-2">
                            <x-icon name="flag" class="h-4 w-4 text-amber-800" />
                            Ngày kết thúc <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', date('Y-m-d', strtotime($itinerary->end_date))) }}" class="field-control" required>
                        @error('end_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="label-quiet flex items-center gap-2">
                        <x-icon name="note" class="h-4 w-4 text-emerald-800" />
                        Ghi chú chuyến đi
                    </label>
                    <textarea name="description" id="description" rows="4" class="field-control">{{ old('description', $itinerary->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">
                        <x-icon name="pencil" class="h-4 w-4" />
                        Cập nhật
                    </button>
                    <a href="{{ route('itineraries.show', $itinerary) }}" class="action-secondary">
                        <x-icon name="x" class="h-4 w-4" />
                        Hủy
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
