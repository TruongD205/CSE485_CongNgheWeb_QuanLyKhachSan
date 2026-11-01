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

            $table->unsignedInteger('RoomID');

            $table->string('GuestName');
            $table->string('GuestPhone');

            $table->string('GuestEmail')->nullable();
            $table->date('CheckInDate');

            $table->date('CheckOutDate')->nullable();

            $table->enum('BookingStatus', ['0', '1', '2'])->default('0');

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
