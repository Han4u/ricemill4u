<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            // Ubah kolom 'kategori' dari ENUM ketat → VARCHAR(100) agar fleksibel
            DB::statement("ALTER TABLE keuangan_ricemill MODIFY COLUMN kategori VARCHAR(100) NOT NULL DEFAULT 'lainnya'");
        } else {
            // PostgreSQL: change column type using Schema builder
            Schema::table('keuangan_ricemill', function (Blueprint $table) {
                $table->string('kategori', 100)->default('lainnya')->change();
            });
        }
    }

    public function down(): void
    {
        // No-op for safety
    }
};
