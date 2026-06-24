<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-amber">
                    <x-icon name="users" class="h-5 w-5" />
                </span>
                <div>
                    <a href="{{ route('groups.show', $group) }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Quay lại tổng quan
                    </a>
                    <h1 class="section-title mt-2">Thành viên nhóm</h1>
                    <p class="section-subtitle">{{ $group->name }} · Vai trò của bạn: {{ ucfirst($membershipRole) }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('createdInviteUrl'))
            <section class="surface-panel border-emerald-200 p-5">
                <label for="created-invite-url" class="label-quiet flex items-center gap-2">
                    <x-icon name="link" class="h-4 w-4 text-emerald-800" />
                    Link mời vừa tạo
                </label>
                <div class="mt-2 flex flex-col gap-2 sm:flex-row">
                    <input id="created-invite-url" type="text" readonly value="{{ session('createdInviteUrl') }}" class="field-control mt-0 font-mono text-xs">
                    <button type="button" class="action-secondary" onclick="navigator.clipboard.writeText(document.getElementById('created-invite-url').value)">
                        <x-icon name="link" class="h-4 w-4" />
                        Copy
                    </button>
                </div>
            </section>
        @endif

        @include('groups.partials.nav', ['group' => $group])

        <div class="grid gap-6 xl:grid-cols-[0.56fr_0.44fr]">
            <section class="surface-panel p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="icon-tile icon-tile-sky h-10 w-10">
                        <x-icon name="users" class="h-4 w-4" />
                    </span>
                    <h2 class="card-title">Danh sách thành viên</h2>
                </div>

                <div class="mt-5 overflow-hidden rounded-lg border border-stone-200">
                    @foreach($group->members as $member)
                        <div class="flex items-center justify-between gap-3 border-b border-stone-100 bg-white px-4 py-3 last:border-b-0">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-stone-950">{{ $member->name }}</p>
                                <p class="truncate text-xs text-stone-500">{{ $member->email }}</p>
                            </div>
                            <span class="badge">{{ ucfirst($member->pivot->role) }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            @can('manageInvites', $group)
                <section class="surface-panel p-5 sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="icon-tile icon-tile-amber h-10 w-10">
                            <x-icon name="mail" class="h-4 w-4" />
                        </span>
                        <h2 class="card-title">Link mời</h2>
                    </div>

                    <form action="{{ route('groups.invites.store', $group) }}" method="POST" class="mt-5 space-y-4">
                        @csrf

                        <div>
                            <label for="role" class="label-quiet">Quyền khi tham gia</label>
                            <select name="role" id="role" class="field-control">
                                <option value="editor" @selected(old('role') === 'editor')>Editor - xem và chỉnh lịch trình</option>
                                <option value="viewer" @selected(old('role') === 'viewer')>Viewer - chỉ xem lịch trình</option>
                            </select>
                            @error('role') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-1">
                            <div>
                                <label for="duration" class="label-quiet">Thời hạn link</label>
                                <select name="duration" id="duration" class="field-control">
                                    <option value="24_hours" @selected(old('duration', '24_hours') === '24_hours')>24 giờ</option>
                                    <option value="1_hour" @selected(old('duration') === '1_hour')>1 giờ</option>
                                    <option value="7_days" @selected(old('duration') === '7_days')>7 ngày</option>
                                    <option value="30_days" @selected(old('duration') === '30_days')>30 ngày</option>
                                </select>
                                @error('duration') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="max_uses" class="label-quiet">Số lượt dùng</label>
                                <input type="number" min="1" max="100" name="max_uses" id="max_uses" value="{{ old('max_uses', 1) }}" class="field-control">
                                @error('max_uses') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit" class="action-primary w-full">
                            <x-icon name="link" class="h-4 w-4" />
                            Tạo link mời
                        </button>
                    </form>

                    @if($invites->isNotEmpty())
                        <div class="mt-6 space-y-3 border-t border-stone-200 pt-5">
                            @foreach($invites as $invite)
                                <div class="rounded-lg border border-stone-200 bg-stone-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-stone-950">{{ ucfirst($invite->role) }}</p>
                                            <p class="mt-1 text-xs text-stone-500">
                                                {{ $invite->uses_count }}/{{ $invite->max_uses }} lượt dùng · hết hạn {{ $invite->expires_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        @if($invite->revoked_at)
                                            <span class="badge-danger">Thu hồi</span>
                                        @elseif($invite->isExpired())
                                            <span class="badge">Hết hạn</span>
                                        @else
                                            <form action="{{ route('groups.invites.destroy', [$group, $invite]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-700 hover:text-red-900">Thu hồi</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endcan
        </div>
    </div>
</x-app-layout>
