<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-stone-200/90 bg-stone-50/90 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-3 text-stone-950">
                    <span class="grid h-10 w-10 place-items-center rounded-lg border border-stone-300 bg-white shadow-sm">
                        <x-application-logo class="h-6 w-6 text-emerald-900" />
                    </span>
                    <span class="hidden font-display text-xl font-semibold sm:inline">Travel Planner</span>
                </a>

                <div class="hidden items-center gap-1 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Tổng quan
                    </x-nav-link>
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        {{ __('Danh mục') }}
                    </x-nav-link>
                    <x-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                        {{ __('Địa điểm') }}
                    </x-nav-link>
                    <x-nav-link :href="route('itineraries.index')" :active="request()->routeIs('itineraries.*')">
                        {{ __('Lịch trình') }}
                    </x-nav-link>

                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                            Người dùng
                        </x-nav-link>
                        <x-nav-link :href="route('admin.itineraries')" :active="request()->routeIs('admin.itineraries')">
                            Kiểm duyệt
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <span class="max-w-40 truncate text-sm font-semibold text-stone-700">{{ Auth::user()->name }}</span>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm font-semibold text-stone-700 shadow-sm hover:border-stone-500 hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                            Tài khoản
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Hồ sơ
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Đăng xuất
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white p-2 text-stone-700 shadow-sm hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-stone-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Tổng quan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                {{ __('Danh mục') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('locations.index')" :active="request()->routeIs('locations.*')">
                {{ __('Địa điểm') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('itineraries.index')" :active="request()->routeIs('itineraries.*')">
                {{ __('Lịch trình') }}
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('Người dùng') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.itineraries')" :active="request()->routeIs('admin.itineraries')">
                    {{ __('Kiểm duyệt') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-stone-200 px-4 py-4">
            <div class="font-semibold text-stone-900">{{ Auth::user()->name }}</div>
            <div class="text-sm text-stone-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Hồ sơ
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Đăng xuất
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
