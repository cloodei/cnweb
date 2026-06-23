<x-admin-layout>
    <x-slot name="header">
        <div>
            <p class="font-mono text-xs font-semibold text-red-300">ADMIN / ITINERARIES</p>
            <h1 class="mt-2 font-display text-3xl font-semibold leading-tight text-white">Kiểm duyệt lịch trình</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-400">Theo dõi lịch trình thuộc nhóm và gỡ lịch trình khi cần moderation.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-800 bg-emerald-950/60 px-4 py-3 text-sm font-semibold text-emerald-100">{{ session('success') }}</div>
        @endif

        <section class="overflow-hidden rounded-lg border border-stone-800 bg-stone-950">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-stone-900 font-mono text-xs font-semibold text-stone-400">
                        <tr>
                            <th class="px-5 py-3 text-left">Tên lịch trình</th>
                            <th class="px-5 py-3 text-left">Nhóm</th>
                            <th class="px-5 py-3 text-left">Người tạo</th>
                            <th class="px-5 py-3 text-left">Thời gian</th>
                            <th class="px-5 py-3 text-right">Kiểm duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-800">
                        @foreach ($itineraries as $itinerary)
                            <tr class="hover:bg-stone-900/70">
                                <td class="px-5 py-4 text-sm font-semibold text-white">{{ $itinerary->title }}</td>
                                <td class="px-5 py-4">
                                    <div class="text-sm font-semibold text-stone-200">{{ $itinerary->group->name ?? 'Nhóm đã bị xóa' }}</div>
                                    <div class="text-xs text-stone-500">Owner: {{ $itinerary->group->owner->email ?? '' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-sm font-semibold text-stone-200">{{ $itinerary->user->name ?? 'Người dùng đã bị xóa' }}</div>
                                    <div class="text-xs text-stone-500">{{ $itinerary->user->email ?? '' }}</div>
                                </td>
                                <td class="px-5 py-4 font-mono text-sm text-stone-400">
                                    {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('admin.itineraries.destroy', $itinerary) }}" method="POST" onsubmit="return confirm('Gỡ bỏ lịch trình này khỏi hệ thống?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-300 hover:text-red-100">Gỡ bỏ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
