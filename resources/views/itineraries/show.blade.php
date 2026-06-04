<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('itineraries.index') }}" class="text-gray-500 hover:text-gray-700">&larr; Quay lại</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chi tiết: <span class="text-teal-600">{{ $itinerary->title }}</span>
                </h2>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('itineraries.edit', $itinerary) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 font-bold py-2 px-4 rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Sửa thông tin
                </a>

                <a href="{{ route('itineraries.pdf', $itinerary) }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-xl shadow transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Tải PDF
                </a>
                <button onclick="navigator.clipboard.writeText('{{ route('itineraries.shared', $itinerary) }}'); alert('Đã copy link! Bạn có thể gửi cho bạn bè ngay.');" class="bg-blue-100 hover:bg-blue-200 text-blue-700 border border-blue-200 font-bold py-2 px-4 rounded-xl shadow-sm transition-all flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
    Copy Link Share
</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-6">
                
                <div class="w-full lg:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 sticky top-6">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Thêm điểm dừng chân</h3>
                        
                        <form action="{{ route('itineraries.add-location', $itinerary) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="location_id" class="block text-sm font-medium text-gray-700">Chọn địa điểm tham quan</label>
                                <select name="location_id" id="location_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                                    <option value="">-- Bấm vào đây để chọn --</option>
                                    @foreach($allLocations as $loc)
                                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }} ({{ $loc->address }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="visit_time" class="block text-sm font-medium text-gray-700">Thời gian ghé thăm</label>
                                <input type="datetime-local" name="visit_time" id="visit_time" value="{{ old('visit_time') }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                @error('visit_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700">Ghi chú / Hoạt động</label>
                                <textarea name="note" id="note" rows="3" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Ví dụ: Ăn trưa tại đây, chụp ảnh checkin...">{{ old('note') }}</textarea>
                                @error('note') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-teal-600 hover:bg-teal-700 transition-all">
                                Thêm vào lộ trình
                            </button>
                        </form>
                    </div>
                </div>

                <div class="w-full lg:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">
                        <h3 class="text-xl font-extrabold mb-6 text-gray-800">Lộ trình chuyến đi</h3>
                        
                        @if($scheduledLocations->isEmpty())
                            <div class="text-center py-12 text-gray-400">
                                <p class="mb-2 text-lg">Chuyến đi này chưa có điểm dừng nào.</p>
                                <p class="text-sm">Hãy sử dụng form bên trái để bắt đầu thiết kế lịch trình!</p>
                            </div>
                        @else
                            <div class="relative border-l-2 border-teal-200 ml-4 md:ml-6 space-y-8 pb-4">
                                @foreach($scheduledLocations as $sl)
                                    <div class="relative pl-6 md:pl-8">
                                        <span class="absolute -left-[11px] top-1.5 bg-teal-500 w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center"></span>
                                        
                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:bg-white hover:shadow-sm transition-all flex flex-col sm:flex-row gap-4 items-start">
                                            @if($sl->image)
                                                <img src="{{ asset('storage/' . $sl->image) }}" alt="" class="w-full sm:w-32 h-24 object-cover rounded-lg shadow-xs">
                                            @endif
                                            
                                            <div class="flex-1">
                                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                                    <h4 class="text-lg font-bold text-gray-900">{{ $sl->name }}</h4>
                                                    <form action="{{ route('itineraries.remove-stop', ['itinerary' => $itinerary->id, 'stop' => $sl->pivot->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ địa điểm này khỏi chuyến đi?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Gỡ khỏi lịch trình">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </form>
                                                    @if($sl->pivot->visit_time)
                                                        <span class="text-xs font-semibold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-md">
                                                            ⏰ {{ date('d/m/Y H:i', strtotime($sl->pivot->visit_time)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-500 mb-2">📍 {{ $sl->address ?? 'Chưa cập nhật địa chỉ' }}</p>
                                                @if($sl->pivot->note)
                                                    <div class="bg-white p-3 rounded-lg border border-dashed border-gray-200 text-sm text-gray-600 mt-2">
                                                        <span class="font-bold text-xs text-gray-400 uppercase tracking-wider block mb-1">Ghi chú của bạn:</span>
                                                        {{ $sl->pivot->note }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
