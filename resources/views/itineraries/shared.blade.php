<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chuyến đi: {{ $itinerary->title }}</title>
    @include('layouts.partials.fonts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <div class="min-h-screen">
        <header class="mx-auto flex max-w-4xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-stone-950">
                <span class="grid h-10 w-10 place-items-center rounded-lg border border-stone-300 bg-white">
                    <x-application-logo class="h-6 w-6 text-emerald-900" />
                </span>
                <span class="font-display text-xl font-semibold">Travel Planner</span>
            </a>
            <span class="badge">
                <x-icon name="lock" class="h-3.5 w-3.5" />
                Bản xem chỉ đọc
            </span>
        </header>

        <main class="mx-auto max-w-4xl px-4 pb-12 sm:px-6 lg:px-8">
            <section class="surface-panel overflow-hidden">
                <div class="border-b border-stone-200 bg-white p-6 text-center sm:p-8">
                    <p class="text-sm font-semibold text-emerald-900">Lịch trình được chia sẻ</p>
                    <h1 class="mt-3 font-display text-4xl font-semibold leading-tight text-stone-950">{{ $itinerary->title }}</h1>
                    <p class="mt-3 inline-flex items-center gap-2 text-sm text-stone-600">
                        <x-icon name="calendar" class="h-4 w-4 text-emerald-800" />
                        {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                    </p>
                    <p class="mt-4 inline-flex rounded-full border border-stone-200 bg-stone-100 px-3 py-1 text-sm font-semibold text-stone-700">
                        <x-icon name="user" class="mr-1.5 h-4 w-4" />
                        Người lập: {{ $itinerary->user->name }}
                    </p>
                </div>

                <div class="p-5 sm:p-8">
                    @if($itinerary->description)
                        <div class="mb-6 rounded-lg border border-stone-200 bg-stone-50 p-4">
                            <p class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500">
                                <x-icon name="note" class="h-4 w-4" />
                                Ghi chú chuyến đi
                            </p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $itinerary->description }}</p>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-emerald h-10 w-10">
                            <x-icon name="route" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Lộ trình chi tiết</h2>
                    </div>

                    @if($scheduledLocations->isEmpty())
                        <div class="empty-state mt-5">Chuyến đi này đang được lên kế hoạch, chưa có điểm dừng nào.</div>
                    @else
                        <div class="mt-5 space-y-4">
                            @foreach($scheduledLocations as $sl)
                                <article class="timeline-card">
                                    <div class="flex flex-col gap-4 sm:flex-row">
                                        @if($sl->image)
                                            <img src="{{ Storage::disk('public')->url($sl->image) }}" alt="{{ $sl->name }}" class="h-32 w-full rounded-md border border-stone-200 object-cover sm:w-40">
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-stone-950">{{ $sl->name }}</h3>
                                                    <p class="mt-1 inline-flex items-center gap-1.5 text-sm text-stone-600">
                                                        <x-icon name="map-pin" class="h-4 w-4 text-sky-800" />
                                                        {{ $sl->address ?? 'Chưa có địa chỉ' }}
                                                    </p>
                                                </div>
                                                @if($sl->pivot->visit_time)
                                                    <span class="badge-accent">
                                                        <x-icon name="clock" class="h-3.5 w-3.5" />
                                                        {{ date('H:i - d/m/Y', strtotime($sl->pivot->visit_time)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($sl->pivot->note)
                                                <div class="mt-4 rounded-md border border-dashed border-stone-300 bg-white p-3">
                                                    <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500">
                                                        <x-icon name="note" class="h-3.5 w-3.5" />
                                                        Ghi chú
                                                    </p>
                                                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-stone-700">{{ $sl->pivot->note }}</p>
                                                </div>
                                            @endif

                                            <div class="mt-4 h-44 overflow-hidden rounded-md border border-stone-200 bg-stone-100">
                                                <iframe
                                                    width="100%"
                                                    height="100%"
                                                    frameborder="0"
                                                    style="border:0"
                                                    src="https://maps.google.com/maps?q={{ urlencode($sl->mapSearchQuery()) }}&t=&z=14&ie=UTF8&iwloc=&output=embed"
                                                    allowfullscreen>
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                <footer class="border-t border-stone-200 bg-stone-50 px-6 py-5 text-center">
                    <p class="inline-flex items-center justify-center gap-2 text-sm text-stone-600">
                        <x-icon name="lock" class="h-4 w-4" />
                        Link này chỉ dùng để xem lịch trình, không cấp quyền chỉnh sửa.
                    </p>
                    <a href="{{ url('/') }}" class="link-quiet mt-2 inline-flex text-sm">Mở Travel Planner</a>
                </footer>
            </section>
        </main>
    </div>
</body>
</html>
