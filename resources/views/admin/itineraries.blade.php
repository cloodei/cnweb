<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 leading-tight border-b-2 border-red-500 pb-2 inline-block">
            🛡️ Kiểm duyệt Lịch trình Cộng đồng
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-6 rounded-xl bg-green-50 border border-green-100 text-green-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tên Lịch Trình</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Người Tạo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Kiểm duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($itineraries as $itinerary)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $itinerary->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $itinerary->user->name ?? 'Người dùng đã bị xóa' }}</div>
                                    <div class="text-xs text-gray-500">{{ $itinerary->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                    <a href="{{ route('itineraries.shared', $itinerary) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Xem thử</a>
                                    
                                    <form action="{{ route('admin.itineraries.destroy', $itinerary) }}" method="POST" onsubmit="return confirm('Gỡ bỏ lịch trình vi phạm này khỏi hệ thống?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Gỡ bỏ (Xóa)</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>