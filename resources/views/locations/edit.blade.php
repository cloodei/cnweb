<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('locations.index') }}" class="link-quiet text-sm">Quay lại địa điểm</a>
            <h1 class="section-title mt-2">Chỉnh sửa địa điểm</h1>
            <p class="section-subtitle">{{ $location->name }}</p>
        </div>
    </x-slot>

    <div class="narrow-shell">
        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('locations.update', $location) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="label-quiet block">Tên địa điểm <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="field-control" required>
                    @error('name') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category_id" class="label-quiet block">Danh mục <span class="text-red-600">*</span></label>
                    <select name="category_id" id="category_id" class="field-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $location->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label-quiet block">Hình ảnh</label>

                    @if($location->image)
                        <div class="mt-3">
                            <p class="mb-2 text-sm font-semibold text-stone-500">Ảnh hiện tại</p>
                            <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="h-40 w-full rounded-md border border-stone-200 object-cover sm:w-80">
                        </div>
                    @endif

                    <label for="imageInput" class="mt-4 block text-sm font-semibold text-stone-500">Thay ảnh mới</label>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="field-file">
                    @error('image') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror

                    <div class="mt-4 hidden" id="previewContainer">
                        <p class="mb-2 text-sm font-semibold text-stone-500">Ảnh mới</p>
                        <img id="imagePreview" src="" alt="Ảnh mới xem trước" class="h-40 w-full rounded-md border border-stone-200 object-cover shadow-sm sm:w-80">
                    </div>
                </div>

                <div>
                    <label for="description" class="label-quiet block">Mô tả</label>
                    <textarea name="description" id="description" rows="4" class="field-control">{{ old('description', $location->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="label-quiet block">Địa chỉ thực tế</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $location->address) }}" class="field-control" placeholder="Ví dụ: Phường Bãi Cháy, TP. Hạ Long">
                    @error('address') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">Cập nhật</button>
                    <a href="{{ route('locations.index') }}" class="action-secondary">Hủy</a>
                </div>
            </form>
        </section>
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
