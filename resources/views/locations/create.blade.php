<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-sky">
                <x-icon name="plus" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('locations.index') }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại địa điểm
                </a>
                <h1 class="section-title mt-2">Thêm địa điểm</h1>
                <p class="section-subtitle">Địa điểm mới sẽ xuất hiện trong kho dùng chung.</p>
            </div>
        </div>
    </x-slot>

    <div class="page-shell">
        <form action="{{ route('locations.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-[0.58fr_0.42fr]">
            @csrf

            <section class="surface-panel p-6 sm:p-8">
                <div class="space-y-6">
                <div>
                    <label for="name" class="label-quiet flex items-center gap-2">
                        <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                        Tên địa điểm <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="field-control" required placeholder="Ví dụ: Vịnh Hạ Long">
                    @error('name') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="imageInput" class="label-quiet flex items-center gap-2">
                        <x-icon name="image" class="h-4 w-4 text-amber-800" />
                        Hình ảnh
                    </label>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="field-file">
                    @error('image') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror

                    <div class="mt-4 hidden" id="previewContainer">
                        <p class="mb-2 text-sm font-semibold text-stone-500">Xem trước</p>
                        <img id="imagePreview" src="" alt="Ảnh xem trước" class="h-52 w-full rounded-md border border-stone-200 object-cover shadow-sm sm:w-96">
                    </div>
                </div>

                <div>
                    <label for="description" class="label-quiet flex items-center gap-2">
                        <x-icon name="note" class="h-4 w-4 text-emerald-800" />
                        Mô tả chi tiết
                    </label>
                    <textarea name="description" id="description" rows="4" class="field-control" placeholder="Nhập giới thiệu về địa điểm này">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="label-quiet flex items-center gap-2">
                        <x-icon name="map" class="h-4 w-4 text-sky-800" />
                        Địa chỉ thực tế
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="field-control" placeholder="Ví dụ: Phường Bãi Cháy, TP. Hạ Long">
                    @error('address') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">
                        <x-icon name="plus" class="h-4 w-4" />
                        Lưu địa điểm
                    </button>
                    <a href="{{ route('locations.index') }}" class="action-secondary">
                        <x-icon name="x" class="h-4 w-4" />
                        Hủy
                    </a>
                </div>
                </div>
            </section>

            <aside class="surface-panel h-fit p-5 sm:p-6 xl:sticky xl:top-24">
                @include('shared.place-picker', [
                    'googleMapsKey' => $googleMapsKey,
                    'place' => null,
                    'pickerId' => 'shared-location-picker',
                    'title' => 'Chọn từ Google Maps',
                    'description' => 'Tìm trên Maps để tự điền tên, địa chỉ và tọa độ cho kho địa điểm chung.',
                ])
            </aside>
        </form>
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
