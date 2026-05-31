<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Danh mục Địa điểm') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <span class="font-medium">Thành công!</span> {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Thêm Danh mục mới</h3>
                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Tên danh mục</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Ví dụ: Biển, Núi..." required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Thêm ngay
                            </button>
                        </form>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-bold mb-4 text-gray-800">Danh sách Danh mục</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
    <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
        {{ $category->name }}
    </a>
</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block form-delete">
    @csrf
    @method('DELETE')
    <button type="button" class="text-red-600 hover:text-red-900 font-bold btn-delete">Xóa</button>
</form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Chưa có danh mục nào. Hãy thêm mới!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                    text: "Hành động này sẽ xóa vĩnh viễn danh mục!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#6b7280', 
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
</script>
</x-app-layout>