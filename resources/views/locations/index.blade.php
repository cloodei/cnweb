<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-900">Kho chung</p>
                <h1 class="section-title">Địa điểm</h1>
                <p class="section-subtitle">Danh sách điểm đến dùng chung. Người đóng góp hoặc admin có thể chỉnh sửa.</p>
            </div>
            <a href="{{ route('locations.create') }}" class="action-primary">Thêm địa điểm</a>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <section class="surface-panel p-4 sm:p-5">
            <form action="{{ route('locations.index') }}" method="GET" class="grid gap-3 md:grid-cols-[1fr_16rem_auto]">
                <div>
                    <label for="search" class="sr-only">Tìm tên hoặc địa chỉ</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}" class="field-control mt-0" placeholder="Tìm tên hoặc địa chỉ">
                </div>

                <div>
                    <label for="category_id" class="sr-only">Danh mục</label>
                    <select id="category_id" name="category_id" class="field-control mt-0">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="action-primary flex-1 md:flex-none">Lọc</button>
                    @if(request()->has('search') || request()->has('category_id'))
                        <a href="{{ route('locations.index') }}" class="action-secondary flex-1 md:flex-none">Xóa lọc</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-5 py-3">Ảnh</th>
                            <th class="px-5 py-3">Địa điểm</th>
                            <th class="px-5 py-3">Danh mục</th>
                            <th class="px-5 py-3 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locations as $location)
                            <tr class="table-row">
                                <td class="table-cell">
                                    @if($location->image)
                                        <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="h-16 w-24 rounded-md border border-stone-200 object-cover">
                                    @else
                                        <div class="flex h-16 w-24 items-center justify-center rounded-md border border-dashed border-stone-300 bg-stone-100 text-xs font-semibold text-stone-400">Trống</div>
                                    @endif
                                </td>

                                <td class="table-cell min-w-64">
                                    <a href="{{ route('locations.show', $location) }}" class="link-quiet text-base">{{ $location->name }}</a>
                                    <p class="mt-1 max-w-md truncate text-sm text-stone-500" title="{{ $location->description }}">{{ $location->description ?? 'Không có mô tả' }}</p>
                                </td>

                                <td class="table-cell">
                                    <span class="badge-accent">{{ $location->category->name }}</span>
                                </td>

                                <td class="table-cell text-right">
                                    @if(Auth::user()->role === 'admin' || Auth::id() === $location->user_id)
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('locations.edit', $location) }}" class="font-semibold text-stone-700 hover:text-emerald-900">Sửa</a>

                                            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline-block form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="font-semibold text-red-700 hover:text-red-900 btn-delete">Xóa</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-sm text-stone-400">Chỉ xem</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12">
                                    <div class="empty-state">Không tìm thấy địa điểm nào.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
                <div class="border-t border-stone-200 bg-stone-50 px-5 py-4">
                    {{ $locations->links() }}
                </div>
            @endif
        </section>
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
                        if (result.isConfirmed) { form.submit(); }
                    })
                });
            });
        });
    </script>
</x-app-layout>
