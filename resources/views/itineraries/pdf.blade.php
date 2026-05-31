<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch trình: {{ $itinerary->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d9488; padding-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #0d9488; text-transform: uppercase; margin: 0; }
        .meta { color: #666; margin-top: 5px; font-style: italic; }
        table { w-full; border-collapse: collapse; margin-top: 20px; width: 100%; }
        th { background-color: #f3f4f6; padding: 10px; border: 1px solid #ddd; text-align: left; font-weight: bold; }
        td { padding: 10px; border: 1px solid #ddd; vertical-align: top; }
        .time { font-weight: bold; color: #0d9488; }
        .note { font-size: 12px; color: #666; margin-top: 5px; background: #f9fafb; padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $itinerary->title }}</h1>
        <p class="meta">Khởi hành: {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - Kết thúc: {{ date('d/m/Y', strtotime($itinerary->end_date)) }}</p>
        <p>Người lập: {{ Auth::user()->name }}</p>
    </div>

    @if($itinerary->description)
        <div style="margin-bottom: 20px;">
            <strong>Mục tiêu chuyến đi:</strong> {{ $itinerary->description }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th width="20%">Thời gian</th>
                <th width="35%">Địa điểm</th>
                <th width="40%">Hoạt động / Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scheduledLocations as $index => $loc)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="time">
                        {{ $loc->pivot->visit_time ? date('d/m/Y H:i', strtotime($loc->pivot->visit_time)) : 'Chưa xếp lịch' }}
                    </td>
                    <td>
                        <strong>{{ $loc->name }}</strong><br>
                        <span style="font-size: 12px; color: #666;">{{ $loc->address }}</span>
                    </td>
                    <td>
                        @if($loc->pivot->note)
                            <div class="note">{{ $loc->pivot->note }}</div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>