<x-admin-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.users') }}" class="text-sm font-semibold text-red-300 hover:text-red-100">Quay lại người dùng</a>
            <p class="mt-4 font-mono text-xs font-semibold text-red-300">ADMIN / USERS / #{{ $user->id }}</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-white">Chỉnh sửa người dùng</h1>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-800 bg-red-950/70 px-4 py-3 text-sm font-semibold text-red-100">{{ session('error') }}</div>
        @endif

        <section class="rounded-lg border border-stone-800 bg-stone-950 p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-semibold text-stone-300">Tên người dùng</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="mt-2 block w-full rounded-lg border-stone-700 bg-stone-900 text-white focus:border-red-600 focus:ring-red-600" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-stone-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="mt-2 block w-full rounded-lg border-stone-700 bg-stone-900 text-white focus:border-red-600 focus:ring-red-600" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-stone-300">Vai trò</label>
                    <select id="role" name="role" class="mt-2 block w-full rounded-lg border-stone-700 bg-stone-900 text-white focus:border-red-600 focus:ring-red-600" required>
                        <option value="user" @selected(old('role', $user->role) === 'user')>Người dùng</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Quản trị viên</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-stone-800 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center justify-center rounded-lg border border-stone-700 px-4 py-2.5 text-sm font-semibold text-stone-200 hover:bg-stone-900">Hủy</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Lưu thay đổi</button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
