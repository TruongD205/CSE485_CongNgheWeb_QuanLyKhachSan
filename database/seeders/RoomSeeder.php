<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['101', 'don', 450000, 2, '0', 'Phòng đơn tầng 1, ban công hướng phố, đầy đủ tiện nghi'],
            ['102', 'don', 500000, 2, '0', 'Phòng đơn tầng 1, gần thang máy, cửa sổ thoáng mát'],
            ['103', 'don', 500000, 2, '0', 'Phòng đơn tầng 1, nội thất hiện đại, không gian yên tĩnh'],
            ['201', 'doi', 800000, 3, '0', 'Phòng đôi tầng 2, 2 giường lớn, hướng hồ bơi'],
            ['202', 'doi', 850000, 3, '0', 'Phòng đôi tầng 2, có bồn tắm nằm cao cấp'],
            ['203', 'doi', 900000, 4, '0', 'Phòng đôi gia đình tầng 2, có ban công ngắm cảnh'],
            ['301', 'vip', 2000000, 4, '0', 'Phòng VIP tầng 3, view toàn cảnh thành phố, phòng khách riêng'],
            ['302', 'vip', 2500000, 5, '0', 'Phòng Tổng Thống cao cấp nhất khách sạn, dịch vụ 5 sao'],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['RoomNumber' => $room[0]],
                [
                    'RoomType' => $room[1],
                    'RoomPrice' => $room[2],
                    'RoomCapacity' => $room[3],
                    'RoomStatus' => $room[4],
                    'RoomDescription' => $room[5],
                ]
            );
        }
    }
}
