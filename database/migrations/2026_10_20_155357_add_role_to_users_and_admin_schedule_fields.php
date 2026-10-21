
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan role untuk membedakan admin dan pengguna biasa.
        if (Schema::hasTable('users') &&
            !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 20)
                    ->default('user')
                    ->index();
            });
        }

        // Tambahkan kolom jadwal hanya jika tabelnya sudah tersedia.
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if (!Schema::hasColumn('schedules', 'nama_bus')) {
                    $table->string('nama_bus', 100)->nullable();
                }

                if (!Schema::hasColumn('schedules', 'waktu_tiba')) {
                    $table->dateTime('waktu_tiba')->nullable();
                }

                if (!Schema::hasColumn('schedules', 'kapasitas')) {
                    $table->unsignedSmallInteger('kapasitas')
                        ->default(0);
                }

                if (!Schema::hasColumn('schedules', 'kursi_tersedia')) {
                    $table->unsignedSmallInteger('kursi_tersedia')
                        ->default(0);
                }

                if (!Schema::hasColumn('schedules', 'fasilitas')) {
                    $table->text('fasilitas')->nullable();
                }

                if (!Schema::hasColumn('schedules', 'status')) {
                    $table->string('status', 20)
                        ->default('scheduled')
                        ->index();
                }
            });
        }
    }

    public function down(): void
    {
        // Kolom jadwal sengaja tidak dihapus untuk melindungi data lama.
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['role']);
                $table->dropColumn('role');
            });
        }
    }
};
