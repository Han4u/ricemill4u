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
            // MySQL-specific ENUM changes
            DB::statement("ALTER TABLE pengiriman_beras
                MODIFY COLUMN status ENUM('menunggu','dikirim','diterima','ditolak','diproses') NOT NULL DEFAULT 'menunggu'");

            DB::statement("ALTER TABLE pengiriman_beras
                MODIFY COLUMN jenis_beras ENUM('premium','medium','setra_ramos','pandan_wangi','biasa') NOT NULL DEFAULT 'medium'");

            DB::statement("ALTER TABLE hasil_pengemasan
                MODIFY COLUMN jenis_kemasan ENUM('5kg','10kg','25kg','50kg') NOT NULL DEFAULT '5kg'");

            DB::statement("ALTER TABLE hasil_pengemasan
                MODIFY COLUMN kualitas ENUM('layak_jual','reject') NOT NULL DEFAULT 'layak_jual'");

            DB::statement("ALTER TABLE hasil_pengemasan
                MODIFY COLUMN jenis_beras ENUM('premium','medium','setra_ramos','pandan_wangi','biasa') NOT NULL DEFAULT 'medium'");

            DB::statement("ALTER TABLE pesanan
                MODIFY COLUMN jenis_produk VARCHAR(100) NOT NULL");

            DB::statement("ALTER TABLE pesanan
                MODIFY COLUMN status ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu'");

            DB::statement("ALTER TABLE penerimaan_beras
                MODIFY COLUMN status ENUM('menunggu','diterima','ditolak','sebagian') NOT NULL DEFAULT 'menunggu'");

            DB::statement("ALTER TABLE penerimaan_beras
                MODIFY COLUMN jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium'");
        } else {
            // PostgreSQL: columns are already created as string/varchar in the
            // initial create migrations, so just ensure column types are correct
            // PostgreSQL doesn't need ALTER for ENUM widening since we use varchar

            // Change pesanan.jenis_produk to varchar if it was an enum
            Schema::table('pesanan', function (Blueprint $table) {
                $table->string('jenis_produk', 100)->change();
            });

            // Change penerimaan_beras.jenis_beras to varchar if it was an enum
            Schema::table('penerimaan_beras', function (Blueprint $table) {
                $table->string('jenis_beras', 100)->default('medium')->change();
            });
        }
    }

    public function down(): void
    {
        // No-op for safety — reversing column type changes is risky
    }
};
