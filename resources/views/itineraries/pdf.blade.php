<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch trình: {{ $itinerary->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.55; color: #292524; background: #fbfaf7; }
        .header { text-align: center; margin-bottom: 28px; border-bottom: 2px solid #166534; padding-bottom: 14px; }
        .title { font-size: 24px; font-weight: bold; color: #1c1917; margin: 0; }
        .meta { color: #57534e; margin-top: 6px; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; background: #fff; }
        th { background-color: #f5f5f4; padding: 10px; border: 1px solid #d6d3d1; text-align: left; font-weight: bold; color: #44403c; }
        td { padding: 10px; border: 1px solid #d6d3d1; vertical-align: top; }
        .time { font-weight: bold; color: #166534; }
        .note { font-size: 12px; color: #57534e; margin-top: 5px; background: #f5f5f4; padding: 6px; border: 1px dashed #d6d3d1; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $itinerary->title }}</h1>
        <p class="meta">Khởi hành: {{ date('d/m/Y', strtotime($itinerary->start_date)) }} - Kết thúc: {{ date('d/m/Y', strtotime($itinerary->end_date)) }}</p>
        <p>Nhóm: {{ $group->name }}</p>
        <p>Người xuất: {{ Auth::user()->name }}</p>
    </div>

    @if($itinerary->description)
        <div style="margin-bottom: 20px;">
            <strong>Ghi chú chuyến đi:</strong> {{ $itinerary->description }}
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
            @foreach($scheduledStops as $index => $stop)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="time">
                        {{ $stop->visit_time ? $stop->visit_time->format('d/m/Y H:i') : 'Chưa xếp lịch' }}
                    </td>
                    <td>
                        <strong>{{ $stop->destinationName() }}</strong><br>
                        <span style="font-size: 12px; color: #666;">{{ $stop->destinationAddress() }}</span><br>
                        <span style="font-size: 11px; color: #78716c;">{{ $stop->sourceLabel() }}</span>
                    </td>
                    <td>
                        @if($stop->note)
                            <div class="note">{{ $stop->note }}</div>
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
