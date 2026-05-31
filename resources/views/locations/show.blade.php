<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('locations.index') }}" class="text-gray-500 hover:text-gray-700 font-medium transition-colors">
                &larr; Quay lại danh sách
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-2 border-gray-300 pl-4">
                Chi tiết Địa điểm
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100">
                
                @if($location->image)
                    <div class="w-full h-80 overflow-hidden bg-gray-100 relative">
                        <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full font-bold text-indigo-700 shadow-sm">
                            {{ $location->category->name }}
                        </div>
                    </div>
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400">
                        <span class="text-lg">Không có hình ảnh</span>
                    </div>
                @endif

                <div class="p-8 sm:p-10">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $location->name }}</h1>
                    
                    <div class="flex items-center gap-2 mb-8 text-sm text-gray-500 border-b border-gray-100 pb-6">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $location->address ?? 'Đang cập nhật địa chỉ' }}</span>
                    </div>

                    <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Mô tả chi tiết:</h3>
                        <p class="whitespace-pre-line">{{ $location->description ?? 'Chưa có thông tin mô tả chi tiết cho địa điểm này.' }}</p>
                    </div>
<div class="mt-8 border-t border-gray-100 pt-6">
    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
        Vị trí trên bản đồ
    </h3>
    <div class="w-full h-80 rounded-2xl overflow-hidden shadow-inner border border-gray-200 bg-gray-50">
        @if($location->address)
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                style="border:0" 
                src="https://maps.google.com/maps?q={{ urlencode($location->address) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                allowfullscreen>
            </iframe>
        @else
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                style="border:0" 
                src="https://maps.google.com/maps?q={{ urlencode($location->name) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                allowfullscreen>
            </iframe>
        @endif
    </div>
</div>
                    <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-500">
                            Đóng góp bởi: <span class="font-bold text-gray-700">{{ $location->user->name ?? 'Người dùng ẩn danh' }}</span>
                        </div>
                        
                        @if(Auth::user()->role === 'admin' || Auth::id() === $location->user_id)
                            <div class="flex items-center gap-3">
                                <a href="{{ route('locations.edit', $location) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 px-6 rounded-xl transition-colors">
                                    Sửa bài
                                </a>
                                <form action="{{ route('locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-6 rounded-xl transition-colors">
                                        Xóa bài
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>