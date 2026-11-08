<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $primaryKey = 'InvoiceID';

    protected $fillable = [
        'InvoiceID',
        'InvoiceCode',
        'BookingID',
        'RoomAmount',
        'ServiceAmount',
        'TotalAmount',
        'PaymentMethod',
        'PaymentStatus',
        'PaymentDate',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }
}
