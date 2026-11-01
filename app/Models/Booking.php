<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'BookingID';

    public function room()
    {
        return $this->belongsTo(Room::class, "RoomID", "RoomID");
    }

    protected $casts = [
        'CheckInDate' => 'date',
        'CheckOutDate' => 'date',
    ];

    protected $fillable = ['BookingID', 'RoomID', 'GuestName', 'GuestPhone', 'GuestEmail', 'CheckInDate', 'CheckOutDate', 'BookingStatus'];

    public function nights()
    {
        if (!$this->CheckOutDate) {
            return null;
        }

        return (int) $this->CheckInDate->diffInDays($this->CheckOutDate);
    }
}
