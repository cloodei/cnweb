<nav class="flex gap-2 overflow-x-auto border-b border-stone-200 pb-3">
    <a href="{{ route('groups.show', $group) }}" class="{{ request()->routeIs('groups.show') ? 'bg-stone-900 text-white' : 'bg-white text-stone-700 hover:bg-stone-100' }} inline-flex shrink-0 items-center gap-2 rounded-lg border border-stone-200 px-3 py-2 text-sm font-semibold shadow-sm">
        <x-icon name="map" class="h-4 w-4" />
        Tổng quan
    </a>
    <a href="{{ route('groups.destinations.index', $group) }}" class="{{ request()->routeIs('groups.destinations.*') ? 'bg-stone-900 text-white' : 'bg-white text-stone-700 hover:bg-stone-100' }} inline-flex shrink-0 items-center gap-2 rounded-lg border border-stone-200 px-3 py-2 text-sm font-semibold shadow-sm">
        <x-icon name="map-pin" class="h-4 w-4" />
        Địa điểm riêng
    </a>
    <a href="{{ route('groups.itineraries.index', $group) }}" class="{{ request()->routeIs('groups.itineraries.*') ? 'bg-stone-900 text-white' : 'bg-white text-stone-700 hover:bg-stone-100' }} inline-flex shrink-0 items-center gap-2 rounded-lg border border-stone-200 px-3 py-2 text-sm font-semibold shadow-sm">
        <x-icon name="route" class="h-4 w-4" />
        Lịch trình
    </a>
    <a href="{{ route('groups.members', $group) }}" class="{{ request()->routeIs('groups.members') ? 'bg-stone-900 text-white' : 'bg-white text-stone-700 hover:bg-stone-100' }} inline-flex shrink-0 items-center gap-2 rounded-lg border border-stone-200 px-3 py-2 text-sm font-semibold shadow-sm">
        <x-icon name="users" class="h-4 w-4" />
        Thành viên
    </a>
</nav>
