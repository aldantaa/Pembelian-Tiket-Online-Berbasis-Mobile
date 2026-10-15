<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // KF-04: satu nomor kursi tidak boleh dobel dalam satu jadwal
    public function up(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->unique(['schedule_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropUnique(['schedule_id', 'seat_number']);
        });
    }
};
