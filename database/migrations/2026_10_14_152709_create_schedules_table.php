
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Tabel schedules sudah dibuat oleh migrasi
     * 2026_10_07_152119_create_schedules_table.php.
     */
    public function up(): void
    {
        // Tidak perlu membuat tabel schedules lagi.
    }

    public function down(): void
    {
        // Jangan menghapus tabel schedules dari migrasi ini.
    }
};
