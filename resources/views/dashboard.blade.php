<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="map" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Tổng quan</p>
                    <h1 class="section-title">Không gian lập lịch trình</h1>
                    <p class="section-subtitle">Theo dõi kho địa điểm dùng chung và bắt đầu tạo lịch trình cá nhân của bạn.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-8">
        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="stat-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Địa điểm</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $totalLocations }}</p>
                    </div>
                    <span class="icon-tile icon-tile-sky">
                        <x-icon name="map-pin" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Điểm đến đã được đóng góp vào kho chung.</p>
            </div>

            <div class="stat-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Lịch trình của bạn</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">{{ $totalItineraries }}</p>
                    </div>
                    <span class="icon-tile icon-tile-emerald">
                        <x-icon name="calendar" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Kế hoạch chuyến đi thuộc tài khoản hiện tại.</p>
            </div>

            <div class="stat-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-stone-500">Chia sẻ</p>
                        <p class="mt-3 font-display text-4xl font-semibold text-stone-950">PDF</p>
                    </div>
                    <span class="icon-tile icon-tile-amber">
                        <x-icon name="link" class="h-5 w-5" />
                    </span>
                </div>
                <p class="mt-3 text-sm text-stone-500">Xuất lịch trình hoặc gửi link xem chỉ đọc khi kế hoạch sẵn sàng.</p>
            </div>
        </section>

        <section class="surface-panel overflow-hidden">
            <div class="grid gap-0 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="p-6 sm:p-8">
                    <span class="meta-pill text-emerald-900">
                        <x-icon name="sparkles" class="h-4 w-4" />
                        Bắt đầu nhanh
                    </span>
                    <h2 class="mt-3 card-title">Thêm địa điểm, rồi xếp chúng vào lịch trình.</h2>
                    <p class="mt-4 text-sm leading-6 text-stone-600">
                        Kho địa điểm là nội dung dùng chung. Lịch trình là không gian riêng của bạn, nơi mỗi điểm dừng có thời gian ghé thăm và ghi chú riêng.
                    </p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('locations.create') }}" class="action-primary">
                            <x-icon name="plus" class="h-4 w-4" />
                            Thêm địa điểm
                        </a>
                        <a href="{{ route('itineraries.create') }}" class="action-secondary">
                            <x-icon name="calendar" class="h-4 w-4" />
                            Tạo lịch trình
                        </a>
                    </div>
                </div>

                <div
                    class="min-h-72 bg-stone-900 bg-cover bg-center p-6 text-white lg:min-h-[20rem]"
                    style="background-image: linear-gradient(120deg, rgba(28, 25, 23, .84), rgba(28, 25, 23, .24)), url('https://images.unsplash.com/photo-1499678329028-101435549a4e?auto=format&fit=crop&w=1200&q=85');"
                    role="img"
                    aria-label="Góc bàn với bản đồ và sổ tay du lịch"
                >
                    <div class="flex h-full min-h-60 flex-col justify-end">
                        <p class="inline-flex w-fit items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 font-mono text-xs font-semibold text-stone-100 backdrop-blur">
                            <x-icon name="lock" class="h-3.5 w-3.5" />
                            READ ONLY SHARE
                        </p>
                        <p class="mt-2 max-w-sm font-display text-2xl font-semibold leading-tight">Xuất PDF hoặc gửi link xem khi lịch trình đã sẵn sàng.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('locations.index') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-sky h-10 w-10">
                        <x-icon name="search" class="h-4 w-4" />
                    </span>
                    <h3 class="text-base font-semibold text-stone-950">Địa điểm</h3>
                </div>
                <p class="mt-2 text-sm leading-6 text-stone-600">Tìm kiếm, xem và đóng góp thêm địa điểm cho kho chung.</p>
            </a>
            <a href="{{ route('itineraries.index') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-emerald h-10 w-10">
                        <x-icon name="route" class="h-4 w-4" />
                    </span>
                    <h3 class="text-base font-semibold text-stone-950">Lịch trình</h3>
                </div>
                <p class="mt-2 text-sm leading-6 text-stone-600">Quản lý kế hoạch chuyến đi cá nhân, PDF và link chia sẻ chỉ đọc.</p>
            </a>
            <a href="{{ route('itineraries.create') }}" class="surface-panel p-5 hover:border-emerald-300 hover:bg-white">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-amber h-10 w-10">
                        <x-icon name="file-text" class="h-4 w-4" />
                    </span>
                    <h3 class="text-base font-semibold text-stone-950">Bản chia sẻ</h3>
                </div>
                <p class="mt-2 text-sm leading-6 text-stone-600">Chuẩn bị kế hoạch để xuất PDF hoặc gửi đường dẫn xem công khai.</p>
            </a>
        </section>
    </div>
</x-app-layout>
