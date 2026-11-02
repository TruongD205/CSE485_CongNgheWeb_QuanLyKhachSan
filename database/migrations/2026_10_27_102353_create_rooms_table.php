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
        Schema::create('rooms', function (Blueprint $table) {
            $table->integerIncrements('RoomID');

            $table->string('RoomNumber')->unique();

            $table->enum('RoomType', ['don', 'doi', 'vip'])->default('don');

            $table->unsignedInteger('RoomPrice');

            $table->unsignedTinyInteger('RoomCapacity')->default(2);

            $table->enum('RoomStatus', ['0', '1', '2'])->default('0');
            // mặc định 0 là trống, 1 là đã đặt, 2 là đang ở

            $table->text('RoomDescription')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
