<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700">&larr; Quay lại</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Danh mục: <span class="text-indigo-600">{{ $category->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Các địa điểm thuộc "{{ $category->name }}"</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($locations as $location)
                        <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">Không có ảnh</div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-bold text-lg text-gray-900">{{ $location->name }}</h4>
                                <p class="text-sm text-gray-500 mt-2">{{ str()->limit($location->description, 80) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            Chưa có địa điểm nào trong danh mục này. 
                            <a href="{{ route('locations.create') }}" class="text-indigo-600 font-bold">Thêm ngay!</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>