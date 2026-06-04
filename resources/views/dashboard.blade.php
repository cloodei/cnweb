<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng điều khiển (Dashboard)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-all">
                    <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Danh mục</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $totalCategories }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-all">
                    <div class="p-4 rounded-full bg-teal-50 text-teal-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Địa điểm</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $totalLocations }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-all">
                    <div class="p-4 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Chuyến đi của bạn</p>
                        <p class="text-3xl font-extrabold text-gray-800">{{ $totalItineraries }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-8 text-gray-900 flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Travel Welcome" class="w-full h-64 object-cover rounded-xl mb-8 shadow-md">
                    
                    <h3 class="text-3xl font-extrabold text-gray-800 mb-4 tracking-tight">Bắt đầu lên kế hoạch cho chuyến đi</h3>
                    <p class="text-lg text-gray-500 max-w-2xl mb-8">Thêm địa điểm vào kho chung, sau đó tạo lịch trình riêng của bạn với thời gian ghé thăm và ghi chú cho từng điểm dừng.</p>
                    
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('locations.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            + Thêm Địa Điểm Mới
                        </a>
                        <a href="{{ route('itineraries.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                            ✈️ Lên Lịch Trình Ngay
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
