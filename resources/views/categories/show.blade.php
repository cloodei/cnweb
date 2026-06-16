<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a href="{{ route('categories.index') }}" class="link-quiet text-sm">Quay lại danh mục</a>
                <h1 class="section-title mt-2">{{ $category->name }}</h1>
                <p class="section-subtitle">Các địa điểm đang thuộc danh mục này.</p>
            </div>
            <span class="badge-accent">{{ $locations->count() }} địa điểm</span>
        </div>
    </x-slot>

    <div class="page-shell">
        <section class="surface-panel p-5 sm:p-6">
            @if($locations->isEmpty())
                <div class="empty-state">
                    <p>Chưa có địa điểm nào trong danh mục này.</p>
                    <a href="{{ route('locations.create') }}" class="link-quiet mt-3 inline-flex">Thêm địa điểm đầu tiên</a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($locations as $location)
                        <a href="{{ route('locations.show', $location) }}" class="group overflow-hidden rounded-lg border border-stone-200 bg-white shadow-sm hover:border-emerald-300">
                            @if($location->image)
                                <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="h-48 w-full object-cover">
                            @else
                                <div class="flex h-48 w-full items-center justify-center bg-stone-100 text-sm font-semibold text-stone-400">Không có ảnh</div>
                            @endif
                            <div class="p-4">
                                <h2 class="text-base font-semibold text-stone-950 group-hover:text-emerald-900">{{ $location->name }}</h2>
                                <p class="mt-2 text-sm leading-6 text-stone-600">{{ str()->limit($location->description, 96) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
