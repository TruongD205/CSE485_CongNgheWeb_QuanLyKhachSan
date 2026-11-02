<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'ServiceID';

    protected $fillable = [
        'ServiceID',
        'ServiceName',
        'Price',
        'Unit',
        'Description',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services', 'ServiceID', 'BookingID')
                    ->withPivot('BookingServiceID', 'Quantity', 'Price', 'TotalPrice');
    }
}

