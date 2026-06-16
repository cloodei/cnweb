<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-semibold text-emerald-900">Kho chung</p>
            <h1 class="section-title">Danh mục địa điểm</h1>
            <p class="section-subtitle">Các nhóm địa điểm dùng để lọc và tổ chức kho chung.</p>
        </div>
    </x-slot>

    @php($isAdmin = Auth::user()->isAdmin())

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 {{ $isAdmin ? 'lg:grid-cols-[0.42fr_0.58fr]' : '' }}">
            @if ($isAdmin)
                <section class="surface-panel p-6">
                    <h2 class="card-title">Thêm danh mục</h2>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Danh mục chỉ nên tạo khi có nhóm địa điểm rõ ràng trong kho chung.</p>

                    <form action="{{ route('categories.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="label-quiet block">Tên danh mục</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                class="field-control"
                                placeholder="Ví dụ: Biển, bảo tàng, núi"
                                required
                            >
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="action-primary w-full">Thêm danh mục</button>
                    </form>
                </section>
            @endif

            <section class="{{ $isAdmin ? '' : 'max-w-5xl' }}">
                <div class="table-shell">
                    <div class="border-b border-stone-200 px-5 py-4">
                        <h2 class="card-title">Danh sách</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="table-head">
                                <tr>
                                    <th scope="col" class="px-5 py-3">STT</th>
                                    <th scope="col" class="px-5 py-3">Tên danh mục</th>
                                    <th scope="col" class="px-5 py-3">Số địa điểm</th>
                                    @if ($isAdmin)
                                        <th scope="col" class="px-5 py-3 text-right">Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr class="table-row">
                                        <td class="table-cell">{{ $loop->iteration }}</td>
                                        <td class="table-cell">
                                            <a href="{{ route('categories.show', $category) }}" class="link-quiet">
                                                {{ $category->name }}
                                            </a>
                                        </td>
                                        <td class="table-cell">
                                            <span class="badge">{{ $category->locations_count }} địa điểm</span>
                                        </td>
                                        @if ($isAdmin)
                                            <td class="table-cell text-right">
                                                @if ((int) $category->locations_count === 0)
                                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block form-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="font-semibold text-red-700 hover:text-red-900 btn-delete">Xóa</button>
                                                    </form>
                                                @else
                                                    <span class="text-sm text-stone-400">Đang có địa điểm</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 4 : 3 }}" class="px-5 py-10 text-center text-sm text-stone-500">
                                            Chưa có danh mục nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @if ($isAdmin)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function () {
                        const form = this.closest('.form-delete');
                        Swal.fire({
                            title: 'Bạn có chắc chắn?',
                            text: 'Hành động này sẽ xóa vĩnh viễn danh mục.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#b91c1c',
                            cancelButtonColor: '#57534e',
                            confirmButtonText: 'Xóa danh mục',
                            cancelButtonText: 'Hủy',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
