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
        Schema::create('accounts', function (Blueprint $table) {
            $table->integerIncrements('AccountID'); // khoá chính do có integerIncrements

            $table->string('Username')->unique(); // unique() ràng buộc ko cho tạo 2 tk có tên trùng nhau

            $table->string('Password');

            $table->string('Gmail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
