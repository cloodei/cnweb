<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sửa thông tin chuyến đi') }}: {{ $itinerary->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('itineraries.show', $itinerary) }}" class="text-gray-600 hover:text-gray-900 font-medium">&larr; Quay lại trang chi tiết</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-8 border">
                <form action="{{ route('itineraries.update', $itinerary) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700">Tên chuyến đi / Tiêu đề <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $itinerary->title) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-bold text-gray-700">Ngày bắt đầu <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d', strtotime($itinerary->start_date))) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-bold text-gray-700">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', date('Y-m-d', strtotime($itinerary->end_date))) }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700">Ghi chú chuyến đi</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ old('description', $itinerary->description) }}</textarea>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-8 border border-transparent shadow text-sm font-bold rounded-xl text-white bg-teal-600 hover:bg-teal-700 transition-all">
                            Cập nhật thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>