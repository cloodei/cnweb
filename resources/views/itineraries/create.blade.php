<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tạo Lịch trình chuyến đi mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('itineraries.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">&larr; Quay lại danh sách</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-8 border">
                <form action="{{ route('itineraries.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700">Tên chuyến đi / Tiêu đề <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Ví dụ: Du hí Hà Nội 3 ngày 2 đêm" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-bold text-gray-700">Ngày bắt đầu <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-bold text-gray-700">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                            @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700">Ghi chú chuyến đi</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Nhập mục tiêu chuyến đi hoặc chuẩn bị hành lý..."></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-3 px-8 border border-transparent shadow text-sm font-bold rounded-xl text-white bg-teal-600 hover:bg-teal-700 transition-all">
                            Khởi tạo chuyến đi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>