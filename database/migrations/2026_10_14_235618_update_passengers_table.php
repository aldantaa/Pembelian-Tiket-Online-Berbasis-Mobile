<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->foreignId('booking_id')->after('id')->constrained('bookings')->cascadeOnDelete();
            $table->string('name', 100)->after('booking_id');
            $table->string('id_number', 30)->nullable()->after('name');
            $table->string('phone', 20)->nullable()->after('id_number');
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['booking_id', 'name', 'id_number', 'phone']);
        });
    }
};
