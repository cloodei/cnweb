<div class="flex h-full flex-col">
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-stone-950">
            <span class="grid h-11 w-11 place-items-center rounded-lg border border-stone-300 bg-white shadow-sm">
                <x-application-logo class="h-7 w-7 text-emerald-900" />
            </span>
            <span class="block font-display text-xl font-semibold leading-none">Travel Planner</span>
        </a>
    </div>

    <nav class="mt-8 space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13.5 12 6l8 7.5V20a1 1 0 0 1-1 1h-5v-6h-4v6H5a1 1 0 0 1-1-1v-6.5Z" />
            </svg>
            Tổng quan
        </x-nav-link>
        <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s6-5.2 6-10A6 6 0 1 0 6 11c0 4.8 6 10 6 10Z" />
                <circle cx="12" cy="11" r="2" stroke-width="2" />
            </svg>
            Địa điểm
        </x-nav-link>
        <x-nav-link :href="route('itineraries.index')" :active="request()->routeIs('itineraries.*')">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4h10v16H7zM9 8h6M9 12h6M9 16h4" />
            </svg>
            Lịch trình
        </x-nav-link>
    </nav>

    <div class="mt-auto space-y-3">
        <a href="{{ $profileHref }}" class="{{ $isAdmin ? 'border-red-900 bg-stone-950 text-red-100 hover:bg-red-950' : 'border-stone-300 bg-white text-stone-900 hover:bg-stone-100' }} flex items-center gap-3 rounded-lg border p-3 shadow-sm">
            <span class="{{ $isAdmin ? 'border-red-700 bg-red-900 text-red-50' : 'border-emerald-200 bg-emerald-50 text-emerald-900' }} grid h-10 w-10 place-items-center rounded-lg border font-mono text-sm font-semibold">
                @if($isAdmin)
                    AD
                @else
                    {{ $initials }}
                @endif
            </span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-semibold">{{ $isAdmin ? 'Administrator' : $user->name }}</span>
                <span class="{{ $isAdmin ? 'text-red-200' : 'text-stone-500' }} block truncate text-xs">
                    {{ $isAdmin ? 'Mở bảng admin' : 'Hồ sơ cá nhân' }}
                </span>
            </span>
            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
            </svg>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-700 shadow-sm hover:bg-stone-100">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Đăng xuất
            </button>
        </form>
    </div>
</div>
