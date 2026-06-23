<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-mono text-xs font-semibold text-red-300">ADMIN / CONSOLE</p>
                <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-white">Bảng điều hành</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-400">Khu vực moderation riêng cho tài khoản admin. Không dùng làm hồ sơ cá nhân hoặc không gian lập lịch trình.</p>
            </div>
            <span class="inline-flex rounded-lg border border-red-900/60 bg-red-950/60 px-3 py-2 font-mono text-xs font-semibold text-red-100">
                ADMIN MODE
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 md:grid-cols-6">
            <div class="rounded-lg border border-stone-800 bg-stone-900 p-5">
                <p class="font-mono text-xs text-stone-500">USERS</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $totalUsers }}</p>
            </div>
            <div class="rounded-lg border border-red-900/50 bg-red-950/40 p-5">
                <p class="font-mono text-xs text-red-300">ADMINS</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $adminUsers }}</p>
            </div>
            <div class="rounded-lg border border-stone-800 bg-stone-900 p-5">
                <p class="font-mono text-xs text-stone-500">GROUPS</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $totalGroups }}</p>
            </div>
            <div class="rounded-lg border border-stone-800 bg-stone-900 p-5">
                <p class="font-mono text-xs text-stone-500">ITINERARIES</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $totalItineraries }}</p>
            </div>
            <div class="rounded-lg border border-stone-800 bg-stone-900 p-5">
                <p class="font-mono text-xs text-stone-500">LOCATIONS</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $totalLocations }}</p>
            </div>
            <div class="rounded-lg border border-stone-800 bg-stone-900 p-5">
                <p class="font-mono text-xs text-stone-500">CATEGORIES</p>
                <p class="mt-3 font-display text-3xl font-semibold text-white">{{ $totalCategories }}</p>
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-3">
            <a href="{{ route('admin.users') }}" class="rounded-lg border border-stone-800 bg-stone-900 p-6 hover:border-red-800">
                <p class="font-mono text-xs font-semibold text-red-300">01 / USERS</p>
                <h2 class="mt-4 text-xl font-semibold text-white">Quản lý người dùng</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Cập nhật tên, email và vai trò tài khoản mà không xóa dữ liệu người dùng.</p>
            </a>

            <a href="{{ route('admin.itineraries') }}" class="rounded-lg border border-stone-800 bg-stone-900 p-6 hover:border-red-800">
                <p class="font-mono text-xs font-semibold text-red-300">02 / ITINERARIES</p>
                <h2 class="mt-4 text-xl font-semibold text-white">Kiểm duyệt lịch trình</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Theo dõi lịch trình thuộc nhóm và gỡ lịch trình khỏi hệ thống khi cần.</p>
            </a>

            <a href="{{ route('admin.categories') }}" class="rounded-lg border border-stone-800 bg-stone-900 p-6 hover:border-red-800">
                <p class="font-mono text-xs font-semibold text-red-300">03 / CATEGORIES</p>
                <h2 class="mt-4 text-xl font-semibold text-white">Danh mục catalog</h2>
                <p class="mt-3 text-sm leading-6 text-stone-400">Tạo, đổi tên và xóa nhóm địa điểm chưa được sử dụng.</p>
            </a>
        </section>
    </div>
</x-admin-layout>
