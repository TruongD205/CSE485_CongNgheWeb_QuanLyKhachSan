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
        Schema::create('bookings', function (Blueprint $table) {
            $table->integerIncrements('BookingID');

            $table->unsignedInteger('RoomID'); // só nguyên dương

            $table->string('GuestName');
            $table->string('GuestPhone');

            $table->string('GuestEmail')->nullable();
            $table->date('CheckInDate');

            $table->date('CheckOutDate')->nullable();

            $table->enum('BookingStatus', ['0', '1', '2'])->default('0');
            // mặc định 0 là đã xác nhận, 1 là đang ở, 2 là đã trả phòng

            $table->foreign('RoomID')->references('RoomID')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
