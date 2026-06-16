<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-red-700">Khu vực admin</p>
            <h1 class="section-title">Quản lý người dùng</h1>
            <p class="section-subtitle">Danh sách tài khoản và thao tác xóa tài khoản không phải admin.</p>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-5 py-3">ID</th>
                            <th class="px-5 py-3">Tên người dùng</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Vai trò</th>
                            <th class="px-5 py-3 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="table-row">
                                <td class="table-cell">#{{ $user->id }}</td>
                                <td class="table-cell font-semibold text-stone-950">{{ $user->name }}</td>
                                <td class="table-cell">{{ $user->email }}</td>
                                <td class="table-cell">
                                    @if($user->role === 'admin')
                                        <span class="badge-danger">Admin</span>
                                    @else
                                        <span class="badge">Người dùng</span>
                                    @endif
                                </td>
                                <td class="table-cell text-right">
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này vĩnh viễn?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-700 hover:text-red-900">Xóa tài khoản</button>
                                        </form>
                                    @else
                                        <span class="text-sm text-stone-400">Không thể xóa</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
