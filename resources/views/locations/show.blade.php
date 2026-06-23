<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-sky">
                    <x-icon name="map-pin" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('locations.index') }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại địa điểm
                    </a>
                    <h1 class="section-title mt-2">{{ $location->name }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        <span class="meta-pill">
                            <x-icon name="map-pin" class="h-3.5 w-3.5" />
                            {{ $location->address ?? 'Chưa cập nhật địa chỉ' }}
                        </span>
                        <span class="meta-pill">
                            <x-icon name="user" class="h-3.5 w-3.5" />
                            {{ $location->user->name ?? 'Người dùng ẩn danh' }}
                        </span>
                    </div>
                </div>
            </div>

            @if(Auth::user()->role === 'admin' || Auth::id() === $location->user_id)
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('locations.edit', $location) }}" class="action-secondary">
                        <x-icon name="pencil" class="h-4 w-4" />
                        Sửa địa điểm
                    </a>
                    <form action="{{ route('locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-danger w-full">
                            <x-icon name="trash" class="h-4 w-4" />
                            Xóa
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="narrow-shell space-y-6">
        <article class="surface-panel overflow-hidden">
            @if($location->image)
                <img src="{{ Storage::disk('public')->url($location->image) }}" alt="{{ $location->name }}" class="h-80 w-full object-cover">
            @else
                <div class="media-placeholder h-64 w-full">
                    <div class="text-center">
                        <x-icon name="image" class="mx-auto h-8 w-8" />
                        <p class="mt-2">Không có hình ảnh</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-0 lg:grid-cols-[1fr_16rem]">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="note" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Mô tả</h2>
                    </div>
                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $location->description ?? 'Chưa có thông tin mô tả chi tiết cho địa điểm này.' }}</p>
                </div>

                <aside class="border-t border-stone-200 bg-stone-50/70 p-6 lg:border-l lg:border-t-0">
                    <div class="flex items-start gap-3">
                        <span class="icon-tile icon-tile-sky h-10 w-10">
                            <x-icon name="user" class="h-4 w-4" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-stone-500">Đóng góp bởi</p>
                            <p class="mt-1 font-semibold text-stone-950">{{ $location->user->name ?? 'Người dùng ẩn danh' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-start gap-3">
                        <span class="icon-tile icon-tile-amber h-10 w-10">
                            <x-icon name="map" class="h-4 w-4" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-stone-500">Tọa độ tra cứu</p>
                            <p class="mt-1 text-sm leading-6 text-stone-700">{{ $location->address ?? $location->name }}</p>
                        </div>
                    </div>
                </aside>
            </div>
        </article>

        <section class="surface-panel p-5 sm:p-6">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-sky h-10 w-10">
                        <x-icon name="map" class="h-4 w-4" />
                    </span>
                    <div>
                        <h2 class="card-title">Vị trí trên bản đồ</h2>
                        <p class="mt-1 text-sm text-stone-600">{{ $location->address ?? $location->name }}</p>
                    </div>
                </div>
            </div>

            <div class="h-80 overflow-hidden rounded-md border border-stone-200 bg-stone-100">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    style="border:0"
                    src="https://maps.google.com/maps?q={{ urlencode($location->mapSearchQuery()) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen>
                </iframe>
            </div>
        </section>
    </div>
</x-app-layout>
