<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('booking_services')->insertOrIgnore([
            ['BookingServiceID' => 1, 'BookingID' => 1, 'ServiceID' => 1, 'Quantity' => 3, 'Price' => 10000, 'TotalPrice' => 30000],
            ['BookingServiceID' => 2, 'BookingID' => 1, 'ServiceID' => 2, 'Quantity' => 2, 'Price' => 15000, 'TotalPrice' => 30000],
            ['BookingServiceID' => 3, 'BookingID' => 2, 'ServiceID' => 5, 'Quantity' => 3, 'Price' => 60000, 'TotalPrice' => 180000],
            ['BookingServiceID' => 4, 'BookingID' => 2, 'ServiceID' => 3, 'Quantity' => 2, 'Price' => 30000, 'TotalPrice' => 60000],
            ['BookingServiceID' => 5, 'BookingID' => 3, 'ServiceID' => 1, 'Quantity' => 2, 'Price' => 10000, 'TotalPrice' => 20000],
            ['BookingServiceID' => 6, 'BookingID' => 3, 'ServiceID' => 5, 'Quantity' => 2, 'Price' => 60000, 'TotalPrice' => 120000],
            ['BookingServiceID' => 7, 'BookingID' => 4, 'ServiceID' => 4, 'Quantity' => 1, 'Price' => 150000, 'TotalPrice' => 150000],
        ]);

        Invoice::updateOrCreate(
            ['BookingID' => 1],
            [
                'InvoiceCode'   => 'HD-20261102-0001',
                'RoomAmount'    => 1350000,
                'ServiceAmount' => 60000,
                'TotalAmount'   => 1410000,
                'PaymentMethod' => 'tien_mat',
                'PaymentStatus' => '1',
                'PaymentDate'   => '2026-11-02 10:00:00',
            ]
        );

        Invoice::updateOrCreate(
            ['BookingID' => 2],
            [
                'InvoiceCode'   => 'HD-20261103-0002',
                'RoomAmount'    => 2400000,
                'ServiceAmount' => 240000,
                'TotalAmount'   => 2640000,
                'PaymentMethod' => 'chuyen_khoan',
                'PaymentStatus' => '1',
                'PaymentDate'   => '2026-11-03 14:30:00',
            ]
        );
    }
}
