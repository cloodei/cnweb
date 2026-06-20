<x-admin-layout>
    <x-slot name="header">
        <div>
            <p class="font-mono text-xs font-semibold text-red-300">ADMIN / CATEGORIES</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-white">Quản lý danh mục</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-400">Tạo và đổi tên nhóm địa điểm. Danh mục đang chứa địa điểm không thể bị xóa.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-800 bg-emerald-950/60 px-4 py-3 text-sm font-semibold text-emerald-100">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-lg border border-red-800 bg-red-950/70 px-4 py-3 text-sm font-semibold text-red-100">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[20rem_minmax(0,1fr)]">
            <section class="h-fit rounded-lg border border-stone-800 bg-stone-950 p-5">
                <h2 class="font-display text-xl font-semibold text-white">Thêm danh mục</h2>
                <form action="{{ route('admin.categories.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="new-category-name" class="block text-sm font-semibold text-stone-300">Tên danh mục</label>
                        <input
                            id="new-category-name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            class="mt-2 block w-full rounded-lg border-stone-700 bg-stone-900 text-sm text-white focus:border-red-600 focus:ring-red-600"
                            placeholder="Ví dụ: Di sản"
                            required
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-red-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Thêm danh mục
                    </button>
                </form>
            </section>

            <section class="overflow-hidden rounded-lg border border-stone-800 bg-stone-950">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-stone-900 font-mono text-xs font-semibold text-stone-400">
                            <tr>
                                <th class="px-5 py-3 text-left">Danh mục</th>
                                <th class="px-5 py-3 text-left">Địa điểm</th>
                                <th class="px-5 py-3 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-800">
                            @forelse ($categories as $category)
                                <tr class="align-top hover:bg-stone-900/70">
                                    <td class="px-5 py-4">
                                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex min-w-64 gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label for="category-{{ $category->id }}" class="sr-only">Tên danh mục</label>
                                            <input
                                                id="category-{{ $category->id }}"
                                                name="name"
                                                type="text"
                                                value="{{ $category->name }}"
                                                class="block w-full rounded-lg border-stone-700 bg-stone-900 text-sm text-white focus:border-red-600 focus:ring-red-600"
                                                required
                                            >
                                            <button type="submit" class="rounded-lg border border-stone-700 px-3 py-2 text-sm font-semibold text-stone-200 hover:border-stone-500 hover:bg-stone-800">
                                                Lưu
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full border border-stone-700 bg-stone-900 px-2.5 py-1 font-mono text-xs font-semibold text-stone-300">
                                            {{ $category->locations_count }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if ((int) $category->locations_count === 0)
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Xóa danh mục này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-300 hover:text-red-100">Xóa</button>
                                            </form>
                                        @else
                                            <span class="text-sm text-stone-500">Đang được sử dụng</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-center text-sm text-stone-500">Chưa có danh mục nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
