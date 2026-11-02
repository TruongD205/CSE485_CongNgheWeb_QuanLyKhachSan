<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->integerIncrements('BookingServiceID');
            $table->unsignedInteger('BookingID');
            $table->unsignedInteger('ServiceID');
            $table->unsignedInteger('Quantity')->default(1);
            $table->unsignedInteger('Price');
            $table->unsignedInteger('TotalPrice');

            // Khóa ngoại nối sang bookings và services
            $table->foreign('BookingID')->references('BookingID')->on('bookings')->onDelete('cascade');
            $table->foreign('ServiceID')->references('ServiceID')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};

