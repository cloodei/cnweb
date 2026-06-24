<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-sky">
                    <x-icon name="map-pin" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Kho chung</p>
                    <h1 class="section-title">Địa điểm</h1>
                    <p class="section-subtitle">Danh sách điểm đến dùng chung. Người đóng góp hoặc admin có thể chỉnh sửa.</p>
                </div>
            </div>
            <a href="{{ route('locations.create') }}" class="action-primary">
                <x-icon name="plus" class="h-4 w-4" />
                Thêm địa điểm
            </a>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <section class="surface-panel p-4 sm:p-5">
            <form action="{{ route('locations.index') }}" method="GET" class="grid gap-3 md:grid-cols-[1fr_auto]">
                <div class="relative">
                    <label for="search" class="sr-only">Tìm tên hoặc địa chỉ</label>
                    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" />
                    <input id="search" type="text" name="search" value="{{ request('search') }}" class="field-control mt-0 pl-10" placeholder="Tìm tên hoặc địa chỉ">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="action-primary flex-1 md:flex-none">
                        <x-icon name="search" class="h-4 w-4" />
                        Tìm
                    </button>
                    @if(request()->has('search'))
                        <a href="{{ route('locations.index') }}" class="action-secondary flex-1 md:flex-none">
                            <x-icon name="x" class="h-4 w-4" />
                            Xóa
                        </a>
                    @endif
                </div>
            </form>
        </section>

        @if($locations->isEmpty())
            <section class="empty-state">
                <x-icon name="map-pin" class="mx-auto h-8 w-8 text-stone-400" />
                <p class="mt-3 font-semibold text-stone-700">Chưa có địa điểm phù hợp</p>
                <p class="mt-1">Thử từ khóa khác hoặc thêm điểm đến đầu tiên vào kho chung.</p>
                <a href="{{ route('locations.create') }}" class="action-primary mt-5">
                    <x-icon name="plus" class="h-4 w-4" />
                    Thêm địa điểm
                </a>
            </section>
        @else
            <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach($locations as $location)
                    <article class="location-card">
                        <a href="{{ route('locations.show', $location) }}" class="block">
                            @if($location->image)
                                <img src="{{ Storage::disk('public')->url($location->image) }}" alt="{{ $location->name }}" class="h-48 w-full object-cover">
                            @else
                                <div class="media-placeholder h-48 w-full">
                                    <div class="text-center">
                                        <x-icon name="image" class="mx-auto h-8 w-8" />
                                        <p class="mt-2">Không có hình ảnh</p>
                                    </div>
                                </div>
                            @endif
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="meta-pill">
                                    <x-icon name="map-pin" class="h-3.5 w-3.5" />
                                    {{ $location->address ?? 'Chưa cập nhật địa chỉ' }}
                                </span>
                                <span class="meta-pill">
                                    <x-icon name="user" class="h-3.5 w-3.5" />
                                    {{ $location->user->name ?? 'Người dùng ẩn danh' }}
                                </span>
                            </div>

                            <div class="mt-4 flex-1">
                                <a href="{{ route('locations.show', $location) }}" class="card-title hover:text-emerald-900">
                                    {{ $location->name }}
                                </a>
                                <p class="mt-3 clamp-3 text-sm leading-6 text-stone-600">
                                    {{ $location->description ?? 'Chưa có mô tả cho địa điểm này.' }}
                                </p>
                            </div>

                            <div class="mt-5 flex items-center justify-between gap-3 border-t border-stone-100 pt-4">
                                <a href="{{ route('locations.show', $location) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                                    Xem chi tiết
                                    <x-icon name="arrow-right" class="h-4 w-4" />
                                </a>

                                @if(Auth::user()->isAdmin() || Auth::id() === $location->user_id)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('locations.edit', $location) }}" class="action-secondary px-3 py-2" aria-label="Sửa {{ $location->name }}">
                                            <x-icon name="pencil" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('locations.destroy', $location) }}" method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-danger btn-delete px-3 py-2" aria-label="Xóa {{ $location->name }}">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="meta-pill">Chỉ xem</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            @if($locations->hasPages())
                <div class="surface-panel px-5 py-4">
                    {{ $locations->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('.form-delete');

                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: 'Hình ảnh và thông tin địa điểm này sẽ bị xóa vĩnh viễn.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#b91c1c',
                        cancelButtonColor: '#57534e',
                        confirmButtonText: 'Xóa địa điểm',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
