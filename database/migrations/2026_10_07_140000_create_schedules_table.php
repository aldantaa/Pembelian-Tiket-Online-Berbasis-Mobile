
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->string('kota_asal', 100);
            $table->string('kota_tujuan', 100);
            $table->dateTime('waktu_keberangkatan');
            $table->dateTime('waktu_tiba')->nullable();

            $table->string('nama_bus', 100)->nullable();
            $table->unsignedBigInteger('harga');
            $table->unsignedSmallInteger('kapasitas')->default(0);
            $table->unsignedSmallInteger('kursi_tersedia')->default(0);

            $table->text('fasilitas')->nullable();
            $table->string('status', 20)->default('scheduled')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
