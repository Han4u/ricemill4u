<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Drop enum check constraints to allow the new enum/varchar values in PostgreSQL
            DB::statement("ALTER TABLE pengiriman_beras DROP CONSTRAINT IF EXISTS pengiriman_beras_status_check");
            DB::statement("ALTER TABLE pengiriman_beras DROP CONSTRAINT IF EXISTS pengiriman_beras_jenis_beras_check");
            
            DB::statement("ALTER TABLE hasil_pengemasan DROP CONSTRAINT IF EXISTS hasil_pengemasan_jenis_kemasan_check");
            DB::statement("ALTER TABLE hasil_pengemasan DROP CONSTRAINT IF EXISTS hasil_pengemasan_kualitas_check");
            DB::statement("ALTER TABLE hasil_pengemasan DROP CONSTRAINT IF EXISTS hasil_pengemasan_jenis_beras_check");
            
            DB::statement("ALTER TABLE pesanan DROP CONSTRAINT IF EXISTS pesanan_jenis_produk_check");
            DB::statement("ALTER TABLE pesanan DROP CONSTRAINT IF EXISTS pesanan_status_check");
            
            DB::statement("ALTER TABLE penerimaan_beras DROP CONSTRAINT IF EXISTS penerimaan_beras_status_check");
            DB::statement("ALTER TABLE penerimaan_beras DROP CONSTRAINT IF EXISTS penerimaan_beras_jenis_beras_check");
            
            DB::statement("ALTER TABLE keuangan_ricemill DROP CONSTRAINT IF EXISTS keuangan_ricemill_kategori_check");
        }
    }

    public function down(): void
    {
        // Check constraints are not strictly required to be recreated
    }
};
