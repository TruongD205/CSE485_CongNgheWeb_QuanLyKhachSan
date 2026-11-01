<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $today = now();

        $bookings = [
            [
                '101',
                'Đặng Nguyên Trường',
                '0905123456',
                'truongdn@gmail.com',
                $today->copy()->subDays(6)->toDateString(),
                $today->copy()->subDays(3)->toDateString(),
                '2'
            ],
            [
                '102',
                'Nguyễn Đình Kiên',
                '0912777888',
                'kiennd@gmail.com',
                $today->copy()->subDays(2)->toDateString(),
                $today->copy()->addDays(2)->toDateString(),
                '1'
            ],
            [
                '201',
                'Nguyễn Đình Đạt',
                '0938444555',
                'datnd@gmail.com',
                $today->toDateString(),
                $today->copy()->addDays(3)->toDateString(),
                '1'
            ],
            [
                '202',
                'Phạm Văn Nam',
                '0977000111',
                'nam.pv@gmail.com',
                $today->copy()->addDays(3)->toDateString(),
                $today->copy()->addDays(6)->toDateString(),
                '0'
            ],
            [
                '301',
                'Nguyễn Thắng Đức An',
                '0982333444',
                'anntd@gmail.com',
                $today->copy()->addDays(5)->toDateString(),
                $today->copy()->addDays(8)->toDateString(),
                '0'
            ],
            [
                '103',
                'Nguyễn Cao Phước',
                '0966123789',
                'phuocnc@gmail.com',
                $today->copy()->subDays(1)->toDateString(),
                $today->copy()->addDays(2)->toDateString(),
                '1'
            ],
            [
                '203',
                'Bùi Quang Vinh',
                '0978333999',
                'vinhbq@gmail.com',
                $today->copy()->addDays(4)->toDateString(),
                $today->copy()->addDays(7)->toDateString(),
                '0'
            ],
        ];

        foreach ($bookings as $booking) {
            $room = Room::where('RoomNumber', $booking[0])->first();

            if (!$room) {
                continue;
            }

            Booking::updateOrCreate(
                ['RoomID' => $room->RoomID, 'GuestPhone' => $booking[2]],
                [
                    'GuestName' => $booking[1],
                    'GuestEmail' => $booking[3],
                    'CheckInDate' => $booking[4],
                    'CheckOutDate' => $booking[5],
                    'BookingStatus' => $booking[6],
                ]
            );
        }

        foreach (Room::pluck('RoomID') as $roomId) {
            Room::syncStatus($roomId);
        }
    }
}
