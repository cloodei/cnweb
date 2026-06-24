@include('shared.place-picker', [
    'googleMapsKey' => $googleMapsKey,
    'place' => $groupLocation ?? null,
    'pickerId' => 'group-location-picker',
    'title' => 'Chọn từ Google Maps',
    'description' => 'Tìm kiếm địa điểm hoặc bấm trên bản đồ để tự điền tên, địa chỉ và tọa độ.',
    'disabledMessage' => 'Chưa cấu hình Google Maps, nên bản đồ chọn địa điểm đang tắt. Form bên dưới vẫn lưu thủ công bình thường.',
])
