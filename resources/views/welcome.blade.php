<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Travel Planner</title>
    @include('layouts.partials.fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <div class="min-h-screen">
        <header class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-stone-950">
                <span class="grid h-10 w-10 place-items-center rounded-lg border border-stone-300 bg-white shadow-sm">
                    <x-application-logo class="h-6 w-6 text-emerald-900" />
                </span>
                <span class="whitespace-nowrap font-display text-lg font-semibold sm:text-xl">Travel Planner</span>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 text-sm font-semibold">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="action-secondary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden px-3 py-2 text-stone-700 hover:text-stone-950 sm:inline-flex">Đăng nhập</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="action-primary">Tạo tài khoản</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="page-shell pt-6">
            <section class="grid min-h-[calc(100vh-8rem)] items-center gap-10 lg:grid-cols-[1fr_0.86fr]">
                <div class="max-w-2xl">
                    <p class="mb-5 inline-flex rounded-full border border-stone-300 bg-white px-3 py-1 text-sm font-semibold text-stone-700 shadow-sm">
                        Dự án lập lịch trình du lịch cá nhân
                    </p>
                    <h1 class="font-display text-5xl font-semibold leading-[1.04] text-stone-950 sm:text-6xl">
                        Ghi lại địa điểm, xếp lịch trình, rồi chia sẻ bản đọc.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-stone-600">
                        Một ứng dụng Laravel nhỏ gọn cho kho địa điểm dùng chung, lịch trình thuộc từng người dùng, xuất PDF và link chia sẻ chỉ đọc.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="action-primary px-6 py-3">Mở dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="action-primary px-6 py-3">Bắt đầu dùng thử</a>
                            <a href="{{ route('login') }}" class="action-secondary px-6 py-3">Đăng nhập</a>
                        @endauth
                    </div>

                    <dl class="mt-10 grid max-w-xl grid-cols-3 gap-3">
                        <div class="surface-panel p-4">
                            <dt class="flex items-center gap-2 text-sm font-semibold text-stone-500">
                                <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                                Kho địa điểm
                            </dt>
                            <dd class="mt-1 font-display text-2xl font-semibold text-stone-950">Dùng chung</dd>
                        </div>
                        <div class="surface-panel p-4">
                            <dt class="flex items-center gap-2 text-sm font-semibold text-stone-500">
                                <x-icon name="calendar" class="h-4 w-4 text-emerald-800" />
                                Lịch trình
                            </dt>
                            <dd class="mt-1 font-display text-2xl font-semibold text-stone-950">Cá nhân</dd>
                        </div>
                        <div class="surface-panel p-4">
                            <dt class="flex items-center gap-2 text-sm font-semibold text-stone-500">
                                <x-icon name="lock" class="h-4 w-4 text-amber-800" />
                                Chia sẻ
                            </dt>
                            <dd class="mt-1 font-display text-2xl font-semibold text-stone-950">Chỉ đọc</dd>
                        </div>
                    </dl>
                </div>

                <div class="relative">
                    <div class="surface-panel overflow-hidden p-2">
                        <img
                            src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85"
                            alt="Bàn gỗ với sổ tay, bản đồ và vật dụng chuẩn bị chuyến đi"
                            class="h-[30rem] w-full rounded-md object-cover"
                        >
                    </div>
                    <div class="surface-panel absolute -bottom-6 left-6 max-w-xs p-5">
                        <p class="text-sm font-semibold text-stone-500">Mục tiêu hiện tại</p>
                        <p class="mt-2 text-sm leading-6 text-stone-700">
                            Trình bày rõ phần đã làm được: địa điểm, lịch trình cá nhân, PDF, chia sẻ chỉ đọc và moderation cơ bản.
                        </p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
