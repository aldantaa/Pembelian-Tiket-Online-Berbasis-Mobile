<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // satu pembayaran untuk satu booking_code (semua kursi dalam satu pesanan)
    // status: pending | success | failed | expired
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('booking_code', 20)->unique()->after('id');
            $table->foreignId('user_id')->after('booking_code')->constrained('users')->cascadeOnDelete();
            $table->string('method', 30)->after('user_id');
            $table->unsignedBigInteger('amount')->default(0)->after('method');
            $table->string('status', 20)->default('pending')->after('amount')->index();
            $table->string('reference', 40)->nullable()->unique()->after('status');
            $table->timestamp('paid_at')->nullable()->after('reference');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['booking_code']);
            $table->dropUnique(['reference']);
            $table->dropIndex(['status']);
            $table->dropColumn(['booking_code', 'user_id', 'method', 'amount', 'status', 'reference', 'paid_at']);
        });
    }
};
