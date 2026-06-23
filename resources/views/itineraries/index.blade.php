<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-emerald">
                    <x-icon name="route" class="h-5 w-5" />
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Lịch trình</p>
                    <h1 class="section-title">Lịch trình cá nhân</h1>
                    <p class="section-subtitle">Các lịch trình thuộc tài khoản hiện tại. Link chia sẻ chỉ cho phép xem.</p>
                </div>
            </div>
            <a href="{{ route('itineraries.create') }}" class="action-primary">
                <x-icon name="plus" class="h-4 w-4" />
                Tạo lịch trình
            </a>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($itineraries->isEmpty())
            <section class="empty-state">
                <span class="icon-tile icon-tile-emerald mx-auto">
                    <x-icon name="calendar" class="h-5 w-5" />
                </span>
                <h2 class="mt-4 font-display text-2xl font-semibold text-stone-950">Bạn chưa có lịch trình nào.</h2>
                <p class="mt-2 text-sm text-stone-600">Tạo một kế hoạch chuyến đi để bắt đầu xếp các điểm dừng từ kho chung.</p>
                <a href="{{ route('itineraries.create') }}" class="action-primary mt-5">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tạo lịch trình đầu tiên
                </a>
            </section>
        @else
            <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($itineraries as $itinerary)
                    <article class="surface-panel flex flex-col justify-between p-6">
                        <div>
                            <span class="badge-accent">
                                <x-icon name="calendar" class="h-3.5 w-3.5" />
                                {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}
                            </span>
                            <div class="mt-4 flex items-start gap-3">
                                <span class="icon-tile icon-tile-emerald h-10 w-10">
                                    <x-icon name="route" class="h-4 w-4" />
                                </span>
                                <h2 class="card-title">{{ $itinerary->title }}</h2>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-stone-600">{{ $itinerary->description ?? 'Không có mô tả.' }}</p>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-stone-200 pt-4">
                            <a href="{{ route('itineraries.show', $itinerary) }}" class="link-quiet inline-flex items-center gap-1.5">
                                Xem chi tiết
                                <x-icon name="arrow-right" class="h-4 w-4" />
                            </a>

                            <form action="{{ route('itineraries.destroy', $itinerary) }}" method="POST" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-700 hover:text-red-900 btn-delete">
                                    <x-icon name="trash" class="h-4 w-4" />
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('.form-delete');
                    Swal.fire({
                        title: 'Xóa lịch trình này?',
                        text: 'Toàn bộ danh sách địa điểm đã lên lịch cũng sẽ bị gỡ bỏ.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#b91c1c',
                        cancelButtonColor: '#57534e',
                        confirmButtonText: 'Xóa lịch trình',
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
