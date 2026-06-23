<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-emerald-900">Tài khoản</p>
            <h1 class="section-title">Hồ sơ cá nhân</h1>
            <p class="section-subtitle">Cập nhật thông tin đăng nhập và thiết lập bảo mật cơ bản.</p>
        </div>
    </x-slot>

    <div class="page-shell">
        {{-- Thống kê cá nhân --}}
        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-2">
            <div class="surface-panel flex items-center gap-4 p-4">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-stone-900">{{ $itineraryCount }}</p>
                    <p class="text-sm text-stone-500">Lịch trình của bạn</p>
                </div>
            </div>
            <div class="surface-panel flex items-center gap-4 p-4">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-stone-900">{{ $locationCount }}</p>
                    <p class="text-sm text-stone-500">Địa điểm đã đóng góp</p>
                </div>
            </div>
        </div>

        {{-- Form panels --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="surface-panel p-5 sm:p-6 lg:col-span-1">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="surface-panel p-5 sm:p-6 lg:col-span-1">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="surface-panel p-5 sm:p-6 lg:col-span-1">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
