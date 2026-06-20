<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('12345678');
        $userRecords = [
            [
                'name' => 'Quản trị viên',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'usera@gmail.com',
                'role' => 'user',
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'userb@gmail.com',
                'role' => 'user',
            ],
            [
                'name' => 'Lê Minh Châu',
                'email' => 'chau@gmail.com',
                'role' => 'user',
            ],
            [
                'name' => 'Phạm Gia Huy',
                'email' => 'huy@gmail.com',
                'role' => 'user',
            ],
        ];

        $users = collect($userRecords)->mapWithKeys(function (array $userData) use ($password) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $password,
                    'email_verified_at' => now(),
                ],
            );

            $user->forceFill(['role' => $userData['role']])->save();

            return [$userData['email'] => $user];
        });

        $categoryNames = [
            'Biển và đảo',
            'Núi và cao nguyên',
            'Di sản văn hóa',
            'Ẩm thực',
            'Thiên nhiên',
            'Đô thị',
            'Nghỉ dưỡng',
        ];

        $categories = collect($categoryNames)->mapWithKeys(function (string $name) {
            $category = Category::updateOrCreate(['name' => $name]);

            return [$name => $category];
        });

        $locationRecords = [
            [
                'name' => 'Vịnh Hạ Long',
                'category' => 'Biển và đảo',
                'owner' => 'usera@gmail.com',
                'address' => 'Thành phố Hạ Long, Quảng Ninh',
                'description' => 'Di sản thiên nhiên với hàng nghìn đảo đá vôi, phù hợp cho hành trình du thuyền và ngắm hoàng hôn.',
            ],
            [
                'name' => 'Bãi biển Mỹ Khê',
                'category' => 'Biển và đảo',
                'owner' => 'userb@gmail.com',
                'address' => 'Phước Mỹ, Sơn Trà, Đà Nẵng',
                'description' => 'Bãi biển gần trung tâm Đà Nẵng, thuận tiện cho tắm biển, đi bộ sáng sớm và thưởng thức hải sản.',
            ],
            [
                'name' => 'Đảo Phú Quốc',
                'category' => 'Nghỉ dưỡng',
                'owner' => 'chau@gmail.com',
                'address' => 'Phú Quốc, Kiên Giang',
                'description' => 'Điểm nghỉ dưỡng biển với nhiều bãi tắm, làng chài và hoạt động khám phá phía bắc đảo.',
            ],
            [
                'name' => 'Đỉnh Fansipan',
                'category' => 'Núi và cao nguyên',
                'owner' => 'huy@gmail.com',
                'address' => 'Sa Pa, Lào Cai',
                'description' => 'Đỉnh núi cao nhất Việt Nam, có thể tiếp cận bằng cáp treo hoặc hành trình trekking có hướng dẫn.',
            ],
            [
                'name' => 'Đồi chè Cầu Đất',
                'category' => 'Núi và cao nguyên',
                'owner' => 'usera@gmail.com',
                'address' => 'Xuân Trường, Đà Lạt, Lâm Đồng',
                'description' => 'Không gian đồi chè thoáng rộng, đẹp vào buổi sớm và thích hợp kết hợp cùng cung đường ngoại ô Đà Lạt.',
            ],
            [
                'name' => 'Phố cổ Hội An',
                'category' => 'Di sản văn hóa',
                'owner' => 'userb@gmail.com',
                'address' => 'Minh An, Hội An, Quảng Nam',
                'description' => 'Khu phố di sản nổi bật với kiến trúc cổ, đèn lồng, chợ địa phương và không gian đi bộ ven sông.',
            ],
            [
                'name' => 'Đại Nội Huế',
                'category' => 'Di sản văn hóa',
                'owner' => 'chau@gmail.com',
                'address' => 'Phú Hậu, Huế',
                'description' => 'Quần thể cung điện triều Nguyễn, phù hợp cho chuyến tham quan lịch sử kéo dài nửa ngày.',
            ],
            [
                'name' => 'Văn Miếu - Quốc Tử Giám',
                'category' => 'Di sản văn hóa',
                'owner' => 'huy@gmail.com',
                'address' => '58 Quốc Tử Giám, Đống Đa, Hà Nội',
                'description' => 'Di tích giáo dục tiêu biểu của Hà Nội với kiến trúc truyền thống và nhiều lớp không gian yên tĩnh.',
            ],
            [
                'name' => 'Chợ Bến Thành',
                'category' => 'Ẩm thực',
                'owner' => 'usera@gmail.com',
                'address' => 'Lê Lợi, Quận 1, Thành phố Hồ Chí Minh',
                'description' => 'Khu chợ trung tâm với nhiều món ăn, đặc sản và quầy hàng phù hợp cho một buổi khám phá nhanh.',
            ],
            [
                'name' => 'Chợ đêm Đà Lạt',
                'category' => 'Ẩm thực',
                'owner' => 'userb@gmail.com',
                'address' => 'Nguyễn Thị Minh Khai, Đà Lạt, Lâm Đồng',
                'description' => 'Điểm dạo tối nhộn nhịp với đồ ăn nóng, nông sản địa phương và không khí se lạnh đặc trưng.',
            ],
            [
                'name' => 'Vườn quốc gia Phong Nha - Kẻ Bàng',
                'category' => 'Thiên nhiên',
                'owner' => 'chau@gmail.com',
                'address' => 'Bố Trạch, Quảng Bình',
                'description' => 'Vùng núi đá vôi và hang động quy mô lớn, có nhiều tuyến tham quan từ nhẹ đến chuyên sâu.',
            ],
            [
                'name' => 'Tràng An',
                'category' => 'Thiên nhiên',
                'owner' => 'huy@gmail.com',
                'address' => 'Hoa Lư, Ninh Bình',
                'description' => 'Tuyến thuyền qua sông, hang xuyên thủy và núi đá vôi, phù hợp cho chuyến đi trong ngày.',
            ],
            [
                'name' => 'Hồ Hoàn Kiếm',
                'category' => 'Đô thị',
                'owner' => 'usera@gmail.com',
                'address' => 'Hoàn Kiếm, Hà Nội',
                'description' => 'Không gian đi bộ trung tâm, thuận tiện kết nối khu phố cổ, nhà hát và các điểm ăn uống.',
            ],
            [
                'name' => 'Bến Bạch Đằng',
                'category' => 'Đô thị',
                'owner' => 'userb@gmail.com',
                'address' => 'Tôn Đức Thắng, Quận 1, Thành phố Hồ Chí Minh',
                'description' => 'Công viên ven sông tại trung tâm thành phố, phù hợp đi bộ chiều tối và ngắm cảnh đô thị.',
            ],
        ];

        $locations = collect($locationRecords)->mapWithKeys(function (array $locationData) use ($categories, $users) {
            $location = Location::updateOrCreate(
                ['name' => $locationData['name']],
                [
                    'category_id' => $categories[$locationData['category']]->id,
                    'user_id' => $users[$locationData['owner']]->id,
                    'description' => $locationData['description'],
                    'address' => $locationData['address'],
                    'image' => null,
                ],
            );

            return [$locationData['name'] => $location];
        });

        $itineraryRecords = [
            [
                'owner' => 'usera@gmail.com',
                'title' => 'Hà Nội cuối tuần',
                'description' => 'Hai ngày khám phá khu trung tâm, di sản và ẩm thực Hà Nội.',
                'start_date' => '2026-07-11',
                'end_date' => '2026-07-12',
                'stops' => [
                    ['location' => 'Hồ Hoàn Kiếm', 'visit_time' => '2026-07-11 07:30:00', 'note' => 'Đi bộ một vòng hồ và ăn sáng gần phố cổ.'],
                    ['location' => 'Văn Miếu - Quốc Tử Giám', 'visit_time' => '2026-07-11 10:00:00', 'note' => 'Dành khoảng 90 phút tham quan.'],
                    ['location' => 'Tràng An', 'visit_time' => '2026-07-12 08:00:00', 'note' => 'Khởi hành sớm từ Hà Nội, đặt vé thuyền tại bến.'],
                ],
            ],
            [
                'owner' => 'usera@gmail.com',
                'title' => 'Hạ Long ngắn ngày',
                'description' => 'Một chuyến nghỉ ngắn với lịch trình gọn quanh vịnh.',
                'start_date' => '2026-08-01',
                'end_date' => '2026-08-02',
                'stops' => [
                    ['location' => 'Vịnh Hạ Long', 'visit_time' => '2026-08-01 13:30:00', 'note' => 'Có mặt tại bến trước giờ tàu 30 phút.'],
                ],
            ],
            [
                'owner' => 'userb@gmail.com',
                'title' => 'Đà Nẵng - Hội An',
                'description' => 'Kết hợp biển, phố cổ và một buổi tối thư thả.',
                'start_date' => '2026-07-20',
                'end_date' => '2026-07-23',
                'stops' => [
                    ['location' => 'Bãi biển Mỹ Khê', 'visit_time' => '2026-07-20 16:30:00', 'note' => 'Nhận phòng trước rồi đi biển.'],
                    ['location' => 'Phố cổ Hội An', 'visit_time' => '2026-07-21 15:00:00', 'note' => 'Ở lại đến tối để xem phố lên đèn.'],
                ],
            ],
            [
                'owner' => 'chau@gmail.com',
                'title' => 'Di sản miền Trung',
                'description' => 'Hành trình chậm qua Huế, Hội An và Phong Nha.',
                'start_date' => '2026-09-03',
                'end_date' => '2026-09-08',
                'stops' => [
                    ['location' => 'Đại Nội Huế', 'visit_time' => '2026-09-03 08:00:00', 'note' => 'Mang nước và ưu tiên tham quan buổi sáng.'],
                    ['location' => 'Phố cổ Hội An', 'visit_time' => '2026-09-05 16:00:00', 'note' => 'Đặt trước chỗ nghỉ trong khu vực đi bộ.'],
                    ['location' => 'Vườn quốc gia Phong Nha - Kẻ Bàng', 'visit_time' => '2026-09-07 07:30:00', 'note' => 'Chọn tuyến hang động phù hợp thể lực cả nhóm.'],
                ],
            ],
            [
                'owner' => 'huy@gmail.com',
                'title' => 'Săn mây Sa Pa',
                'description' => 'Lịch trình vùng cao tập trung vào cảnh quan và trải nghiệm ngoài trời.',
                'start_date' => '2026-10-16',
                'end_date' => '2026-10-19',
                'stops' => [
                    ['location' => 'Đỉnh Fansipan', 'visit_time' => '2026-10-17 07:00:00', 'note' => 'Kiểm tra thời tiết và mua vé cáp treo từ hôm trước.'],
                ],
            ],
        ];

        foreach ($itineraryRecords as $itineraryData) {
            $itinerary = Itinerary::updateOrCreate(
                [
                    'user_id' => $users[$itineraryData['owner']]->id,
                    'title' => $itineraryData['title'],
                ],
                [
                    'description' => $itineraryData['description'],
                    'start_date' => $itineraryData['start_date'],
                    'end_date' => $itineraryData['end_date'],
                ],
            );

            DB::table('itinerary_location')->where('itinerary_id', $itinerary->id)->delete();

            foreach ($itineraryData['stops'] as $stop) {
                DB::table('itinerary_location')->insert([
                    'itinerary_id' => $itinerary->id,
                    'location_id' => $locations[$stop['location']]->id,
                    'visit_time' => $stop['visit_time'],
                    'note' => $stop['note'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
