<x-admin-layout>
    <x-slot name="header">
        <div>
            <p class="font-mono text-xs font-semibold text-red-300">ADMIN / USERS</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-white">Quản lý người dùng</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-400">Danh sách tài khoản và thao tác xóa tài khoản không phải admin.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-800 bg-emerald-950/60 px-4 py-3 text-sm font-semibold text-emerald-100">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="rounded-lg border border-red-800 bg-red-950/70 px-4 py-3 text-sm font-semibold text-red-100">{{ session('error') }}</div>
        @endif

        <section class="overflow-hidden rounded-lg border border-stone-800 bg-stone-950">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-stone-900 font-mono text-xs font-semibold text-stone-400">
                        <tr>
                            <th class="px-5 py-3 text-left">ID</th>
                            <th class="px-5 py-3 text-left">Tên người dùng</th>
                            <th class="px-5 py-3 text-left">Email</th>
                            <th class="px-5 py-3 text-left">Vai trò</th>
                            <th class="px-5 py-3 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-stone-900/70">
                                <td class="px-5 py-4 font-mono text-sm text-stone-500">#{{ $user->id }}</td>
                                <td class="px-5 py-4 text-sm font-semibold text-white">{{ $user->name }}</td>
                                <td class="px-5 py-4 text-sm text-stone-400">{{ $user->email }}</td>
                                <td class="px-5 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex rounded-full border border-red-800 bg-red-950 px-2.5 py-1 font-mono text-xs font-semibold text-red-100">ADMIN</span>
                                    @else
                                        <span class="inline-flex rounded-full border border-stone-700 bg-stone-900 px-2.5 py-1 font-mono text-xs font-semibold text-stone-300">USER</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này vĩnh viễn?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-300 hover:text-red-100">Xóa tài khoản</button>
                                        </form>
                                    @else
                                        <span class="text-sm text-stone-600">Khóa thao tác</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
