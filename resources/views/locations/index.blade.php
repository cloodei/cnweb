<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Quản lý Địa điểm') }}
            </h2>
            
            <a href="{{ route('locations.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                + Thêm Địa điểm mới
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 rounded-xl bg-green-50 border border-green-100 text-green-800 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('locations.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="pl-10 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tìm tên địa điểm...">
                    </div>

                    <div class="md:w-64">
                        <select name="category_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-xl font-medium shadow-sm transition-colors">
                            Lọc
                        </button>
                        @if(request()->has('search') || request()->has('category_id'))
                            <a href="{{ route('locations.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium transition-colors flex items-center">
                                Xóa lọc
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tên Địa Điểm</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Danh Mục</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($locations as $location)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($location->image)
                                            <img src="{{ asset('storage/' . $location->image) }}" alt="Ảnh" class="h-14 w-20 object-cover rounded-lg shadow-sm border border-gray-100">
                                        @else
                                            <div class="h-14 w-20 bg-gray-100 rounded-lg flex items-center justify-center text-xs text-gray-400 border border-dashed border-gray-200">Trống</div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                       <a href="{{ route('locations.show', $location) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900 transition-colors">
    {{ $location->name }} &rarr;
</a>
                                        <div class="text-xs text-gray-500 mt-1 truncate w-48" title="{{ $location->description }}">{{ $location->description ?? 'Không có mô tả' }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $location->category->name }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if(Auth::user()->role === 'admin' || Auth::id() === $location->user_id)
                                            <a href="{{ route('locations.edit', $location) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Sửa</a>
                                            
                                            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline-block form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1.5 rounded-lg transition-colors btn-delete">Xóa</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs italic bg-gray-50 px-3 py-1.5 rounded-lg">Chỉ xem</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Không tìm thấy địa điểm nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($locations->hasPages())
                    <div class="bg-gray-50 border-t border-gray-100 px-6 py-4">
                        {{ $locations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const form = this.closest('.form-delete');
                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Hình ảnh và thông tin địa điểm này sẽ bị xóa vĩnh viễn!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Vâng, xóa nó!',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) { form.submit(); }
                    })
                });
            });
        });
    </script>
</x-app-layout>