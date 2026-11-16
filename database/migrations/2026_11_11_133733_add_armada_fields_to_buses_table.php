<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->string('nama_bus', 100);
            $table->string('nomor_polisi', 20)->nullable()->unique();
            $table->unsignedSmallInteger('kapasitas')->default(40);
            $table->string('status', 20)->default('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropUnique(['nomor_polisi']);
            $table->dropColumn([
                'nama_bus',
                'nomor_polisi',
                'kapasitas',
                'status',
            ]);
        });
    }
};
