<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="users" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Nhóm</p>
                    <h1 class="section-title">Không gian lập lịch trình</h1>
                    <p class="section-subtitle">Mỗi nhóm có thành viên, quyền truy cập và các lịch trình riêng.</p>
                </div>
            </div>
            <a href="{{ route('groups.create') }}" class="action-primary">
                <x-icon name="plus" class="h-4 w-4" />
                Tạo nhóm
            </a>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($groups->isEmpty())
            <section class="empty-state">
                <span class="icon-tile icon-tile-emerald mx-auto">
                    <x-icon name="users" class="h-5 w-5" />
                </span>
                <h2 class="mt-4 font-display text-2xl font-semibold text-stone-950">Bạn chưa có nhóm nào.</h2>
                <p class="mt-2 text-sm text-stone-600">Tạo một nhóm để bắt đầu mời thành viên và quản lý lịch trình chung.</p>
                <a href="{{ route('groups.create') }}" class="action-primary mt-5">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tạo nhóm đầu tiên
                </a>
            </section>
        @else
            <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($groups as $group)
                    <article class="surface-panel flex flex-col justify-between p-6">
                        <div>
                            <div class="flex flex-wrap gap-2">
                                <span class="badge-accent">
                                    <x-icon name="users" class="h-3.5 w-3.5" />
                                    {{ $group->members_count }} thành viên
                                </span>
                                <span class="badge">
                                    <x-icon name="route" class="h-3.5 w-3.5" />
                                    {{ $group->itineraries_count }} lịch trình
                                </span>
                                <span class="badge">
                                    {{ ucfirst($group->pivot->role) }}
                                </span>
                            </div>
                            <div class="mt-4 flex items-start gap-3">
                                <span class="icon-tile icon-tile-emerald h-10 w-10">
                                    <x-icon name="users" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0">
                                    <h2 class="card-title">{{ $group->name }}</h2>
                                    <p class="mt-1 text-xs font-semibold text-stone-500">Chủ nhóm: {{ $group->owner->name }}</p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-stone-600">{{ $group->description ?? 'Chưa có mô tả nhóm.' }}</p>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-stone-200 pt-4">
                            <a href="{{ route('groups.show', $group) }}" class="link-quiet inline-flex items-center gap-1.5">
                                Vào nhóm
                                <x-icon name="arrow-right" class="h-4 w-4" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
</x-app-layout>
