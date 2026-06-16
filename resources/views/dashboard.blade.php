<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-semibold text-emerald-900">Tổng quan</p>
            <h1 class="section-title">Không gian lập lịch trình</h1>
            <p class="section-subtitle">Theo dõi kho địa điểm dùng chung và bắt đầu tạo lịch trình cá nhân của bạn.</p>
        </div>
    </x-slot>

    <div class="page-shell space-y-8">
        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="surface-panel p-6">
                <p class="text-sm font-semibold text-stone-500">Danh mục</p>
                <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $totalCategories }}</p>
                <p class="mt-2 text-sm text-stone-500">Nhóm địa điểm trong kho chung.</p>
            </div>

            <div class="surface-panel p-6">
                <p class="text-sm font-semibold text-stone-500">Địa điểm</p>
                <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $totalLocations }}</p>
                <p class="mt-2 text-sm text-stone-500">Điểm đến đã được đóng góp.</p>
            </div>

            <div class="surface-panel p-6">
                <p class="text-sm font-semibold text-stone-500">Lịch trình của bạn</p>
                <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $totalItineraries }}</p>
                <p class="mt-2 text-sm text-stone-500">Kế hoạch chuyến đi thuộc tài khoản hiện tại.</p>
            </div>
        </section>

        <section class="surface-panel overflow-hidden">
            <div class="grid gap-0 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="p-6 sm:p-8">
                    <p class="text-sm font-semibold text-emerald-900">Bắt đầu nhanh</p>
                    <h2 class="mt-3 card-title">Thêm địa điểm, rồi xếp chúng vào lịch trình.</h2>
                    <p class="mt-4 text-sm leading-6 text-stone-600">
                        Kho địa điểm là nội dung dùng chung. Lịch trình là không gian riêng của bạn, nơi mỗi điểm dừng có thời gian ghé thăm và ghi chú riêng.
                    </p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('locations.create') }}" class="action-primary">Thêm địa điểm</a>
                        <a href="{{ route('itineraries.create') }}" class="action-secondary">Tạo lịch trình</a>
                    </div>
                </div>

                <img
                    src="https://images.unsplash.com/photo-1499678329028-101435549a4e?auto=format&fit=crop&w=1200&q=85"
                    alt="Góc bàn với bản đồ và sổ tay du lịch"
                    class="h-72 w-full object-cover lg:h-full lg:min-h-[20rem]"
                >
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('categories.index') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <h3 class="text-base font-semibold text-stone-950">Danh mục</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Xem các nhóm điểm đến và số lượng địa điểm trong từng nhóm.</p>
            </a>
            <a href="{{ route('locations.index') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <h3 class="text-base font-semibold text-stone-950">Địa điểm</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Tìm kiếm, lọc và đóng góp thêm địa điểm cho kho chung.</p>
            </a>
            <a href="{{ route('itineraries.index') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <h3 class="text-base font-semibold text-stone-950">Lịch trình</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Quản lý kế hoạch chuyến đi cá nhân, PDF và link chia sẻ chỉ đọc.</p>
            </a>
        </section>
    </div>
</x-app-layout>
