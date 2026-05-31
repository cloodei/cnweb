<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chuyến đi: {{ $itinerary->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            
            <div class="bg-teal-600 px-8 py-10 text-white text-center">
                <h1 class="text-3xl font-extrabold mb-2">{{ $itinerary->title }}</h1>
                <p class="text-teal-100">Khởi hành: {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}</p>
                <p class="mt-4 text-sm bg-teal-700 inline-block px-4 py-1 rounded-full border border-teal-500">
                    Chủ phòng: <strong>{{ $itinerary->user->name }}</strong>
                </p>
            </div>
            
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Lộ trình chi tiết</h3>
                
                @if($scheduledLocations->isEmpty())
                    <p class="text-gray-500 text-center py-8">Chuyến đi này đang được lên kế hoạch, chưa có điểm dừng chân nào.</p>
                @else
                    <div class="relative border-l-2 border-teal-200 ml-4 space-y-8 pb-4">
                        @foreach($scheduledLocations as $sl)
                            <div class="relative pl-8">
                                <span class="absolute -left-[11px] top-1.5 bg-teal-500 w-5 h-5 rounded-full border-4 border-white shadow-sm"></span>
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 flex flex-col sm:flex-row gap-4 hover:bg-white hover:shadow-sm transition-all">
                                    @if($sl->image)
                                        <img src="{{ asset('storage/' . $sl->image) }}" class="w-full sm:w-32 h-24 object-cover rounded-lg shadow-sm">
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex flex-wrap justify-between items-start mb-2 gap-2">
                                            <h4 class="text-lg font-bold text-gray-900">{{ $sl->name }}</h4>
                                            @if($sl->pivot->visit_time)
                                                <span class="text-xs font-semibold text-teal-800 bg-teal-100 px-2.5 py-1 rounded-md">
                                                    ⏰ {{ date('H:i - d/m/Y', strtotime($sl->pivot->visit_time)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2">📍 {{ $sl->address ?? 'Chưa có địa chỉ' }}</p>
                                        @if($sl->pivot->note)
                                            <div class="bg-white p-3 rounded-lg border border-dashed border-gray-200 text-sm text-gray-600">
                                                <span class="font-bold text-xs text-gray-400 uppercase tracking-wider block mb-1">Ghi chú:</span>
                                                {{ $sl->pivot->note }}
                                            </div>
                                        @endif

                                        <div class="mt-4 w-full h-44 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
    <iframe 
        width="100%" 
        height="100%" 
        frameborder="0" 
        style="border:0" 
        src="https://maps.google.com/maps?q={{ urlencode($sl->address ?? $sl->name) }}&t=&z=14&ie=UTF8&iwloc=&output=embed" 
        allowfullscreen>
    </iframe>
</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="bg-gray-50 px-8 py-6 text-center border-t border-gray-100">
                <p class="text-sm text-gray-500 mb-2">Bạn cũng muốn tạo một lịch trình tuyệt vời như thế này?</p>
                <a href="{{ url('/') }}" class="text-teal-600 font-bold hover:text-teal-800 hover:underline">
                    Sử dụng Travel App miễn phí ngay &rarr;
                </a>
            </div>
            
        </div>
    </div>
</body>
</html>