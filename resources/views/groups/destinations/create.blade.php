<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-sky">
                <x-icon name="map-pin" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('groups.destinations.index', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại địa điểm riêng
                </a>
                <h1 class="section-title mt-2">Thêm địa điểm riêng</h1>
                <p class="section-subtitle">{{ $group->name }} dùng địa điểm này trong selector lịch trình, không đưa vào kho chung.</p>
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @include('groups.partials.nav', ['group' => $group])

        <form action="{{ route('groups.destinations.store', $group) }}" method="POST" class="grid gap-6 xl:grid-cols-[0.52fr_0.48fr]">
            @csrf

            <section class="surface-panel p-6 sm:p-8">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="label-quiet flex items-center gap-2">
                            <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                            Tên địa điểm <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="field-control" required placeholder="Ví dụ: Khách sạn trung tâm Đà Nẵng">
                        @error('name') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="address" class="label-quiet flex items-center gap-2">
                            <x-icon name="map" class="h-4 w-4 text-sky-800" />
                            Địa chỉ
                        </label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" class="field-control" placeholder="Tự điền từ bản đồ nếu có thể">
                        @error('address') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="label-quiet flex items-center gap-2">
                            <x-icon name="note" class="h-4 w-4 text-emerald-800" />
                            Ghi chú riêng
                        </label>
                        <textarea name="description" id="description" rows="5" class="field-control" placeholder="Ví dụ: điểm hẹn sáng, quán quen, khách sạn dự kiến">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit" class="action-primary">
                            <x-icon name="plus" class="h-4 w-4" />
                            Lưu địa điểm riêng
                        </button>
                        <a href="{{ route('groups.destinations.index', $group) }}" class="action-secondary">
                            <x-icon name="x" class="h-4 w-4" />
                            Hủy
                        </a>
                    </div>
                </div>
            </section>

            <aside class="surface-panel h-fit p-5 sm:p-6 xl:sticky xl:top-24">
                @include('groups.destinations.partials.place-picker', ['googleMapsKey' => $googleMapsKey])
            </aside>
        </form>
    </div>
</x-app-layout>
