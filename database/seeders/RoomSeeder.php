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
            ['201', 'doi', 800000, 3, '0', 'Phòng đôi tầng 2, 2 giường lớn, hướng hồ bơi'],
            ['202', 'doi', 900000, 4, '0', 'Phòng đôi tầng 2, có bồn tắm nằm cao cấp'],
            ['301', 'vip', 2000000, 4, '0', 'Phòng VIP tầng 3, view toàn cảnh thành phố, phòng khách riêng'],
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
