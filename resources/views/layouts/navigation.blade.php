@php
    $user = Auth::user();
    $isAdmin = $user?->isAdmin();
    $profileHref = $isAdmin ? route('admin.dashboard') : route('profile.edit');
    $initials = collect(explode(' ', trim($user->name ?? 'U')))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
@endphp

<div x-data="{ open: false }">
    <div class="fixed inset-x-0 top-0 z-40 flex h-16 items-center justify-between border-b border-stone-200 bg-stone-50/95 px-4 backdrop-blur lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-stone-950">
            <span class="grid h-10 w-10 place-items-center rounded-lg border border-stone-300 bg-white shadow-sm">
                <x-application-logo class="h-6 w-6 text-emerald-900" />
            </span>
            <span class="font-display text-lg font-semibold">Travel Planner</span>
        </a>

        <button @click="open = true" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-stone-300 bg-white text-stone-700 shadow-sm hover:bg-stone-100 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2" aria-label="Mở menu">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" class="fixed inset-0 z-50 lg:hidden" style="display: none;">
        <div class="absolute inset-0 bg-stone-950/40" @click="open = false"></div>
        <aside class="relative flex h-full w-80 max-w-[86vw] flex-col border-r border-stone-200 bg-stone-50 p-4 shadow-xl">
            @include('layouts.partials.sidebar-content', [
                'user' => $user,
                'isAdmin' => $isAdmin,
                'profileHref' => $profileHref,
                'initials' => $initials ?: 'U',
            ])
        </aside>
    </div>

    <aside class="fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-stone-200 bg-stone-50/95 p-4 backdrop-blur lg:flex lg:flex-col">
        @include('layouts.partials.sidebar-content', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'profileHref' => $profileHref,
            'initials' => $initials ?: 'U',
        ])
    </aside>
</div>
