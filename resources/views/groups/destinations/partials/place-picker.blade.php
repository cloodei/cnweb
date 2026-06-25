@include('shared.place-picker', [
    'googleMapsKey' => $googleMapsKey,
    'place' => $groupLocation ?? null,
    'pickerId' => 'group-location-picker',
    'title' => 'Chọn từ bản đồ',
    'description' => 'Tìm kiếm địa điểm hoặc bấm trên bản đồ để tự điền tên, địa chỉ và tọa độ.',
    'disabledMessage' => 'Bản đồ chọn địa điểm đang tắt. Form bên dưới vẫn lưu thủ công bình thường.',
])
