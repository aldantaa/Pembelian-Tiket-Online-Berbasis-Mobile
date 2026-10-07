<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('seats', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel schedules
        $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
        $table->string('seat_number'); // Contoh penamaan: A1, A2, B1
        $table->boolean('is_booked')->default(false); // Menandai kursi sudah dibooking atau belum
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
