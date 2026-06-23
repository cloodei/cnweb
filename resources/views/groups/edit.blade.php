<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-amber">
                <x-icon name="pencil" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('groups.show', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại nhóm
                </a>
                <h1 class="section-title mt-2">Sửa nhóm</h1>
                <p class="section-subtitle">{{ $group->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="narrow-shell">
        <section class="surface-panel p-6 sm:p-8">
            <form action="{{ route('groups.update', $group) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="label-quiet flex items-center gap-2">
                        <x-icon name="users" class="h-4 w-4 text-emerald-800" />
                        Tên nhóm <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" class="field-control" required>
                    @error('name') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="label-quiet flex items-center gap-2">
                        <x-icon name="note" class="h-4 w-4 text-amber-800" />
                        Mô tả nhóm
                    </label>
                    <textarea name="description" id="description" rows="4" class="field-control">{{ old('description', $group->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <button type="submit" class="action-primary">
                        <x-icon name="pencil" class="h-4 w-4" />
                        Cập nhật
                    </button>
                    <a href="{{ route('groups.show', $group) }}" class="action-secondary">
                        <x-icon name="x" class="h-4 w-4" />
                        Hủy
                    </a>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
