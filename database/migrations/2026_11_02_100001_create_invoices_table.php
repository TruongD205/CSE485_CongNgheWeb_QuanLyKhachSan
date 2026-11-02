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
        Schema::create('invoices', function (Blueprint $table) {
            $table->integerIncrements('InvoiceID');
            $table->string('InvoiceCode')->unique();
            $table->unsignedInteger('BookingID');

            $table->unsignedInteger('RoomAmount');
            $table->unsignedInteger('ServiceAmount')->default(0);
            $table->unsignedInteger('TotalAmount');

            $table->enum('PaymentMethod', ['tien_mat', 'chuyen_khoan', 'the'])->default('tien_mat');
            $table->enum('PaymentStatus', ['0', '1'])->default('1');
            $table->dateTime('PaymentDate');

            $table->foreign('BookingID')->references('BookingID')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

