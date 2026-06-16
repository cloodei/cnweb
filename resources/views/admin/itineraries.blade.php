<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-red-700">Khu vực admin</p>
            <h1 class="section-title">Kiểm duyệt lịch trình</h1>
            <p class="section-subtitle">Xem bản chia sẻ chỉ đọc và gỡ lịch trình khi cần moderation.</p>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-5 py-3">Tên lịch trình</th>
                            <th class="px-5 py-3">Người tạo</th>
                            <th class="px-5 py-3">Thời gian</th>
                            <th class="px-5 py-3 text-right">Kiểm duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itineraries as $itinerary)
                            <tr class="table-row">
                                <td class="table-cell font-semibold text-stone-950">{{ $itinerary->title }}</td>
                                <td class="table-cell">
                                    <div class="font-semibold text-stone-900">{{ $itinerary->user->name ?? 'Người dùng đã bị xóa' }}</div>
                                    <div class="text-xs text-stone-500">{{ $itinerary->user->email ?? '' }}</div>
                                </td>
                                <td class="table-cell">
                                    {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                                </td>
                                <td class="table-cell text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('itineraries.shared', $itinerary) }}" target="_blank" class="font-semibold text-emerald-800 hover:text-stone-950">Xem bản chia sẻ</a>

                                        <form action="{{ route('admin.itineraries.destroy', $itinerary) }}" method="POST" onsubmit="return confirm('Gỡ bỏ lịch trình này khỏi hệ thống?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-700 hover:text-red-900">Gỡ bỏ</button>
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
</x-app-layout>
