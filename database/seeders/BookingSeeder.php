<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = [
            [
                'BookingID'     => 1,
                'RoomID'        => 1,
                'GuestName'     => 'Đặng Nguyên Trường',
                'GuestPhone'    => '0905123456',
                'GuestEmail'    => 'truongdn@gmail.com',
                'CheckInDate'   => '2026-10-30',
                'CheckOutDate'  => '2026-11-02',
                'BookingStatus' => '2',
            ],
            [
                'BookingID'     => 2,
                'RoomID'        => 3,
                'GuestName'     => 'Nguyễn Quỳnh Chi',
                'GuestPhone'    => '0918999888',
                'GuestEmail'    => 'quynhchi@gmail.com',
                'CheckInDate'   => '2026-10-31',
                'CheckOutDate'  => '2026-11-03',
                'BookingStatus' => '2',
            ],
            [
                'BookingID'     => 3,
                'RoomID'        => 5,
                'GuestName'     => 'Nguyễn Đình Kiên',
                'GuestPhone'    => '0912777888',
                'GuestEmail'    => 'kiennd@gmail.com',
                'CheckInDate'   => '2026-11-03',
                'CheckOutDate'  => '2026-11-07',
                'BookingStatus' => '1',
            ],
            [
                'BookingID'     => 4,
                'RoomID'        => 2,
                'GuestName'     => 'Nguyễn Cao Phước',
                'GuestPhone'    => '0966123789',
                'GuestEmail'    => 'phuocnc@gmail.com',
                'CheckInDate'   => '2026-11-04',
                'CheckOutDate'  => '2026-11-08',
                'BookingStatus' => '1',
            ],
            [
                'BookingID'     => 5,
                'RoomID'        => 4,
                'GuestName'     => 'Phạm Văn Nam',
                'GuestPhone'    => '0977000111',
                'GuestEmail'    => 'nam.pv@gmail.com',
                'CheckInDate'   => '2026-11-07',
                'CheckOutDate'  => '2026-11-10',
                'BookingStatus' => '0',
            ],
        ];

        foreach ($bookings as $b) {
            Booking::updateOrCreate(['BookingID' => $b['BookingID']], $b);
        }

        foreach (Room::pluck('RoomID') as $roomId) {
            Room::syncStatus($roomId);
        }
    }
}
