<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'RoomID';

    public function bookings()
    {
        return $this->hasMany(Booking::class, "RoomID", "RoomID");
    }

    public function activeBookings()
    {
        return $this->bookings()->whereIn('BookingStatus', ['0', '1']);
    }

    public static function syncStatus($roomId)
    {
        $room = self::find($roomId);
        if (!$room) {
            return;
        }

        if ($room->bookings()->where('BookingStatus', '1')->exists()) {
            $room->update(['RoomStatus' => '2']);
        } elseif ($room->bookings()->where('BookingStatus', '0')->exists()) {
            $room->update(['RoomStatus' => '1']);
        } else {
            $room->update(['RoomStatus' => '0']);
        }
    }

    protected $fillable = ['RoomID', 'RoomNumber', 'RoomType', 'RoomPrice', 'RoomCapacity', 'RoomStatus', 'RoomDescription'];
}
