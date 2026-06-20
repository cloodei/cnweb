<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - Travel Planner</title>

        @include('layouts.partials.fonts')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-stone-950 text-stone-100 lg:pl-72">
            <aside class="fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-red-900/40 bg-stone-950 p-4 lg:flex lg:flex-col">
                <div class="flex items-center gap-3 border-b border-red-900/40 pb-5">
                    <span class="grid h-11 w-11 place-items-center rounded-lg border border-red-800 bg-red-950 font-mono text-sm font-semibold text-red-100">
                        AD
                    </span>
                    <span>
                        <span class="block font-display text-xl font-semibold leading-none text-white">Administrator</span>
                        <span class="mt-1 block font-mono text-xs text-red-200">moderation console</span>
                    </span>
                </div>

                <nav class="mt-8 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-900 text-white' : 'text-stone-300 hover:bg-stone-900 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold">
                        <span class="font-mono text-xs">00</span>
                        Tổng quan admin
                    </a>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-red-900 text-white' : 'text-stone-300 hover:bg-stone-900 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold">
                        <span class="font-mono text-xs">01</span>
                        Người dùng
                    </a>
                    <a href="{{ route('admin.itineraries') }}" class="{{ request()->routeIs('admin.itineraries') ? 'bg-red-900 text-white' : 'text-stone-300 hover:bg-stone-900 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold">
                        <span class="font-mono text-xs">02</span>
                        Kiểm duyệt lịch trình
                    </a>
                    <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'bg-red-900 text-white' : 'text-stone-300 hover:bg-stone-900 hover:text-white' }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold">
                        <span class="font-mono text-xs">03</span>
                        Quản lý danh mục
                    </a>
                </nav>

                <div class="mt-auto space-y-3">
                    <a href="{{ route('dashboard') }}" class="flex w-full items-center justify-center rounded-lg border border-stone-700 bg-stone-900 px-4 py-2.5 text-sm font-semibold text-stone-100 hover:border-stone-500">
                        Về không gian người dùng
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center rounded-lg border border-red-900/50 bg-red-950/60 px-4 py-2.5 text-sm font-semibold text-red-100 hover:bg-red-900">
                            Đăng xuất admin
                        </button>
                    </form>
                </div>
            </aside>

            <div class="border-b border-red-900/40 bg-stone-950/95 px-4 py-4 lg:hidden">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-lg border border-red-800 bg-red-950 font-mono text-sm font-semibold text-red-100">AD</span>
                        <span class="font-display text-lg font-semibold text-white">Administrator</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="rounded-lg border border-stone-700 px-3 py-2 text-sm font-semibold text-stone-200">User</a>
                </div>
                <nav class="mt-4 grid grid-cols-2 gap-2 text-center text-xs font-semibold sm:grid-cols-4">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-900 text-white' : 'bg-stone-900 text-stone-300' }} rounded-lg px-2 py-2">Tổng quan</a>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-red-900 text-white' : 'bg-stone-900 text-stone-300' }} rounded-lg px-2 py-2">Người dùng</a>
                    <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'bg-red-900 text-white' : 'bg-stone-900 text-stone-300' }} rounded-lg px-2 py-2">Danh mục</a>
                    <a href="{{ route('admin.itineraries') }}" class="{{ request()->routeIs('admin.itineraries') ? 'bg-red-900 text-white' : 'bg-stone-900 text-stone-300' }} rounded-lg px-2 py-2">Lịch trình</a>
                </nav>
            </div>

            @isset($header)
                <header class="border-b border-red-900/40 bg-stone-950">
                    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
