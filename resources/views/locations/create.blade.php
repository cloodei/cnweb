<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Địa điểm mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ route('locations.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">&larr; Quay lại danh sách</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('locations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên địa điểm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Ví dụ: Vịnh Hạ Long">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="imageInput" class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                        @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        
                        <div class="mt-4 hidden" id="previewContainer">
                            <p class="text-sm text-gray-500 mb-2">Xem trước:</p>
                            <img id="imagePreview" src="" alt="Preview" class="h-48 w-auto object-cover rounded-lg border shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nhập giới thiệu về địa điểm này..."></textarea>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ thực tế</label>
                        <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ví dụ: Phường Bãi Cháy, TP. Hạ Long">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            Lưu Địa Điểm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                   
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden'); 
                    }
                    reader.readAsDataURL(file);
                } else {
                  
                    imagePreview.src = '';
                    previewContainer.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>