<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::with('room')->where('BookingStatus', '2')->get();

        if ($bookings->isEmpty()) {
            $bookings = Booking::with('room')->take(2)->get();
        }

        $services = Service::all();

        foreach ($bookings as $booking) {
            if (!$booking->room) {
                continue;
            }

            if ($services->count() >= 2 && !DB::table('booking_services')->where('BookingID', $booking->BookingID)->exists()) {
                $s1 = $services[0]; // Nước suối
                $s2 = $services[1]; // Coca
                DB::table('booking_services')->insert([
                    ['BookingID' => $booking->BookingID, 'ServiceID' => $s1->ServiceID, 'Quantity' => 3, 'Price' => $s1->Price, 'TotalPrice' => $s1->Price * 3],
                    ['BookingID' => $booking->BookingID, 'ServiceID' => $s2->ServiceID, 'Quantity' => 1, 'Price' => $s2->Price, 'TotalPrice' => $s2->Price * 1],
                ]);
            }

            $serviceAmount = (int) DB::table('booking_services')->where('BookingID', $booking->BookingID)->sum('TotalPrice');
            $nights = max(1, $booking->nights() ?: 2);
            $roomAmount = $nights * $booking->room->RoomPrice;
            $totalAmount = $roomAmount + $serviceAmount;

            Invoice::updateOrCreate(
                ['BookingID' => $booking->BookingID],
                [
                    'InvoiceCode'   => 'HD-' . date('Ymd') . '-' . str_pad($booking->BookingID, 4, '0', STR_PAD_LEFT),
                    'RoomAmount'    => $roomAmount,
                    'ServiceAmount' => $serviceAmount,
                    'TotalAmount'   => $totalAmount,
                    'PaymentMethod' => 'tien_mat',
                    'PaymentStatus' => '1',
                    'PaymentDate'   => now(),
                ]
            );
        }
    }
}
