<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Địa điểm') }}: {{ $location->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ route('locations.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">&larr; Quay lại</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('locations.update', $location) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT') <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên địa điểm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $location->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                        
                        <div class="mt-2 mb-4">
                            @if($location->image)
                                <p class="text-xs text-gray-500 mb-1">Ảnh hiện tại:</p>
                                <img src="{{ asset('storage/' . $location->image) }}" alt="Current" class="h-32 w-auto object-cover rounded-lg border">
                            @endif
                        </div>

                        <label for="imageInput" class="block text-sm font-medium text-gray-500">Thay ảnh mới (nếu muốn):</label>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        
                        <div class="mt-4 hidden" id="previewContainer">
                            <p class="text-xs text-gray-500 mb-1">Ảnh mới sẽ thay thế:</p>
                            <img id="imagePreview" src="" alt="New Preview" class="h-32 w-auto object-cover rounded-lg border shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $location->description) }}</textarea>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-8 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-all">
                            Cập nhật thay đổi
                        </button>
                        <a href="{{ route('locations.index') }}" class="inline-flex justify-center py-2 px-8 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Hủy
                        </a>
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