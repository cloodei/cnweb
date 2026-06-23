<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <a href="{{ route('locations.index') }}" class="link-quiet text-sm">Quay lại địa điểm</a>
                <h1 class="section-title mt-2">{{ $location->name }}</h1>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="badge-accent">{{ $location->category->name }}</span>
                    <span class="badge">{{ $location->address ?? 'Chưa cập nhật địa chỉ' }}</span>
                </div>
            </div>

            @if(Auth::user()->role === 'admin' || Auth::id() === $location->user_id)
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('locations.edit', $location) }}" class="action-secondary">Sửa địa điểm</a>
                    <form action="{{ route('locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-danger w-full">Xóa</button>
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
                <div class="flex h-64 w-full items-center justify-center bg-stone-100 text-sm font-semibold text-stone-400">
                    Không có hình ảnh
                </div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_16rem]">
                    <div>
                        <h2 class="card-title">Mô tả</h2>
                        <p class="mt-4 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $location->description ?? 'Chưa có thông tin mô tả chi tiết cho địa điểm này.' }}</p>
                    </div>

                    <aside class="surface-panel-soft p-5">
                        <p class="text-sm font-semibold text-stone-500">Đóng góp bởi</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $location->user->name ?? 'Người dùng ẩn danh' }}</p>
                        <p class="mt-5 text-sm font-semibold text-stone-500">Danh mục</p>
                        <p class="mt-2 font-semibold text-stone-950">{{ $location->category->name }}</p>
                    </aside>
                </div>
            </div>
        </article>

        <section class="surface-panel p-5 sm:p-6">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="card-title">Vị trí trên bản đồ</h2>
                    <p class="mt-2 text-sm text-stone-600">{{ $location->address ?? $location->name }}</p>
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
