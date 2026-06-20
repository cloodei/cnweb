<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-semibold text-emerald-900">Kho chung</p>
            <h1 class="section-title">Danh mục địa điểm</h1>
            <p class="section-subtitle">Các nhóm địa điểm dùng để lọc và tổ chức kho chung.</p>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        <section class="max-w-5xl table-shell">
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center text-sm text-stone-500">
                                    Chưa có danh mục nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
