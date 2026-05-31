<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lịch trình du lịch cá nhân') }}
            </h2>
            <a href="{{ route('itineraries.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded shadow transition-all">
                + Tạo kế hoạch mới
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($itineraries as $itinerary)
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 flex flex-col justify-between p-6 hover:shadow-md transition-shadow">
                        <div>
                            <span class="text-xs font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-full">
                                📅 {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                            </span>
                            <h3 class="text-xl font-extrabold text-gray-900 mt-3 mb-2">{{ $itinerary->title }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-3">{{ $itinerary->description ?? 'Không có mô tả.' }}</p>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('itineraries.show', $itinerary) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                                Xem chi tiết &rarr;
                            </a>
                            
                            <form action="{{ route('itineraries.destroy', $itinerary) }}" method="POST" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium btn-delete">Xóa</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white text-center py-12 rounded-xl shadow-sm border">
                        <p class="text-gray-500 mb-4">Bạn chưa có lịch trình du lịch nào.</p>
                        <a href="{{ route('itineraries.create') }}" class="text-teal-600 font-bold hover:underline">Tạo ngay chuyến đi đầu tiên!</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const form = this.closest('.form-delete');
                    Swal.fire({
                        title: 'Hủy chuyến đi này?',
                        text: "Toàn bộ danh sách địa điểm đã lên lịch cũng sẽ bị gỡ bỏ!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Vâng, xóa đi!',
                        cancelButtonText: 'Giữ lại'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>
</x-app-layout>