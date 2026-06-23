<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-4">
            <span class="icon-tile icon-tile-amber">
                <x-icon name="mail" class="h-5 w-5" />
            </span>
            <div>
                <a href="{{ route('groups.index') }}" class="link-quiet inline-flex items-center gap-1.5 text-sm">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Quay lại nhóm
                </a>
                <h1 class="section-title mt-2">Tham gia nhóm</h1>
                <p class="section-subtitle">Link mời này cấp quyền trong nhóm, không mở lịch trình công khai.</p>
            </div>
        </div>
    </x-slot>

    <div class="narrow-shell">
        <section class="surface-panel p-6 sm:p-8">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="users" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Bạn được mời vào</p>
                    <h2 class="mt-1 card-title">{{ $invite->group->name }}</h2>
                    <p class="mt-2 text-sm leading-6 text-stone-600">
                        Chủ nhóm: {{ $invite->group->owner->name }} · Quyền được cấp: {{ ucfirst($invite->role) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <div class="surface-panel-soft p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Hết hạn</p>
                    <p class="mt-2 text-sm font-semibold text-stone-950">{{ $invite->expires_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="surface-panel-soft p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Lượt dùng</p>
                    <p class="mt-2 text-sm font-semibold text-stone-950">{{ $invite->uses_count }}/{{ $invite->max_uses }}</p>
                </div>
                <div class="surface-panel-soft p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Trạng thái</p>
                    <p class="mt-2 text-sm font-semibold text-stone-950">
                        @if($alreadyMember)
                            Đã tham gia
                        @elseif($invite->isRevoked())
                            Đã thu hồi
                        @elseif($invite->isExpired())
                            Đã hết hạn
                        @elseif(! $invite->hasUsesRemaining())
                            Hết lượt dùng
                        @else
                            Có thể tham gia
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                @if($alreadyMember)
                    <a href="{{ route('groups.show', $invite->group) }}" class="action-primary">
                        <x-icon name="arrow-right" class="h-4 w-4" />
                        Vào nhóm
                    </a>
                @elseif($canAccept)
                    <form action="{{ route('group-invites.accept', $token) }}" method="POST">
                        @csrf
                        <button type="submit" class="action-primary">
                            <x-icon name="users" class="h-4 w-4" />
                            Tham gia nhóm
                        </button>
                    </form>
                @else
                    <a href="{{ route('groups.index') }}" class="action-secondary">
                        <x-icon name="arrow-left" class="h-4 w-4" />
                        Về danh sách nhóm
                    </a>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>
