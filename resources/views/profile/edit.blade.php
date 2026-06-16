<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-emerald-900">Tài khoản</p>
            <h1 class="section-title">Hồ sơ cá nhân</h1>
            <p class="section-subtitle">Cập nhật thông tin đăng nhập và thiết lập bảo mật cơ bản.</p>
        </div>
    </x-slot>

    <div class="page-shell">
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
