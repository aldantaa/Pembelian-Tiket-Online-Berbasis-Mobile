<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // satu baris booking = satu kursi; kursi dalam satu pesanan berbagi booking_code
    // status: pending | paid | cancelled | expired
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code', 20)->nullable()->after('id')->index();
            $table->unsignedInteger('price')->default(0)->after('seat_id');
            $table->timestamp('expires_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['booking_code']);
            $table->dropColumn(['booking_code', 'price', 'expires_at']);
        });
    }
};
