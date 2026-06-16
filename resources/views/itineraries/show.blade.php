<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <a href="{{ route('itineraries.index') }}" class="link-quiet text-sm">Quay lại lịch trình</a>
                <h1 class="section-title mt-2">{{ $itinerary->title }}</h1>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="badge-accent">{{ date('d/m/Y', strtotime($itinerary->start_date)) }} - {{ date('d/m/Y', strtotime($itinerary->end_date)) }}</span>
                    <span class="badge">Chỉ chủ sở hữu chỉnh sửa</span>
                </div>
            </div>

            <div class="grid gap-2 sm:flex sm:flex-wrap sm:justify-end">
                <a href="{{ route('itineraries.edit', $itinerary) }}" class="action-secondary">Sửa thông tin</a>
                <a href="{{ route('itineraries.pdf', $itinerary) }}" class="action-secondary">Tải PDF</a>
                <button
                    type="button"
                    onclick="navigator.clipboard.writeText('{{ route('itineraries.shared', $itinerary) }}'); alert('Đã copy link chia sẻ chỉ đọc.');"
                    class="action-primary"
                >
                    Copy link chia sẻ
                </button>
            </div>
        </div>
    </x-slot>

    <div class="page-shell space-y-6">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($itinerary->description)
            <section class="surface-panel p-5 sm:p-6">
                <h2 class="card-title">Ghi chú chuyến đi</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-stone-700">{{ $itinerary->description }}</p>
            </section>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.38fr_0.62fr]">
            <aside class="surface-panel h-fit p-5 sm:p-6 lg:sticky lg:top-24">
                <h2 class="card-title">Thêm điểm dừng</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">Chọn địa điểm từ kho chung rồi thêm thời gian và ghi chú riêng cho lịch trình này.</p>

                <form action="{{ route('itineraries.add-location', $itinerary) }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="location_id" class="label-quiet block">Địa điểm</label>
                        <select name="location_id" id="location_id" class="field-control" required>
                            <option value="">Chọn địa điểm</option>
                            @foreach($allLocations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }}{{ $loc->address ? ' - '.$loc->address : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="visit_time" class="label-quiet block">Thời gian ghé thăm</label>
                        <input type="datetime-local" name="visit_time" id="visit_time" value="{{ old('visit_time') }}" class="field-control">
                        @error('visit_time') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="note" class="label-quiet block">Ghi chú / Hoạt động</label>
                        <textarea name="note" id="note" rows="4" class="field-control" placeholder="Ví dụ: ăn trưa, chụp ảnh, mua vé trước">{{ old('note') }}</textarea>
                        @error('note') <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="action-primary w-full">Thêm vào lộ trình</button>
                </form>
            </aside>

            <section class="surface-panel p-5 sm:p-6">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="card-title">Lộ trình chuyến đi</h2>
                        <p class="mt-2 text-sm text-stone-600">{{ $scheduledLocations->count() }} điểm dừng đã lên lịch.</p>
                    </div>
                </div>

                @if($scheduledLocations->isEmpty())
                    <div class="empty-state">
                        <p>Chuyến đi này chưa có điểm dừng nào.</p>
                        <p class="mt-2">Sử dụng form bên cạnh để bắt đầu xếp lịch.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($scheduledLocations as $sl)
                            <article class="rounded-lg border border-stone-200 bg-stone-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row">
                                    @if($sl->image)
                                        <img src="{{ asset('storage/' . $sl->image) }}" alt="{{ $sl->name }}" class="h-32 w-full rounded-md border border-stone-200 object-cover sm:w-40">
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-stone-950">{{ $sl->name }}</h3>
                                                <p class="mt-1 text-sm text-stone-600">{{ $sl->address ?? 'Chưa cập nhật địa chỉ' }}</p>
                                            </div>

                                            <form action="{{ route('itineraries.remove-stop', ['itinerary' => $itinerary->id, 'stop' => $sl->pivot->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ địa điểm này khỏi chuyến đi?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-700 hover:text-red-900">Gỡ</button>
                                            </form>
                                        </div>

                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @if($sl->pivot->visit_time)
                                                <span class="badge-accent">{{ date('d/m/Y H:i', strtotime($sl->pivot->visit_time)) }}</span>
                                            @else
                                                <span class="badge">Chưa xếp giờ</span>
                                            @endif
                                        </div>

                                        @if($sl->pivot->note)
                                            <div class="mt-4 rounded-md border border-dashed border-stone-300 bg-white p-3">
                                                <p class="text-xs font-semibold text-stone-500">Ghi chú</p>
                                                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-stone-700">{{ $sl->pivot->note }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
