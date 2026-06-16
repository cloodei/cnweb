<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('itineraries.show', $itinerary) }}" class="link-quiet text-sm">Quay lại chi tiết</a>
            <h1 class="section-title mt-2">Sửa lịch trình</h1>
            <p class="section-subtitle">{{ $itinerary->title }}</p>
        </div>
    </x-slot>

    <div class="narrow-shell">
        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('itineraries.update', $itinerary) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="label-quiet block">Tên chuyến đi <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $itinerary->title) }}" class="field-control" required>
                    @error('title') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="label-quiet block">Ngày bắt đầu <span class="text-red-600">*</span></label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d', strtotime($itinerary->start_date))) }}" class="field-control" required>
                        @error('start_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_date" class="label-quiet block">Ngày kết thúc <span class="text-red-600">*</span></label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', date('Y-m-d', strtotime($itinerary->end_date))) }}" class="field-control" required>
                        @error('end_date') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="label-quiet block">Ghi chú chuyến đi</label>
                    <textarea name="description" id="description" rows="4" class="field-control">{{ old('description', $itinerary->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">Cập nhật</button>
                    <a href="{{ route('itineraries.show', $itinerary) }}" class="action-secondary">Hủy</a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
