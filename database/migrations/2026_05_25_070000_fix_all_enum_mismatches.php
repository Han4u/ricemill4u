<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. pengiriman_beras: tambah 'dikirim' ke status, tambah 'setra_ramos','pandan_wangi' ke jenis_beras
        DB::statement("ALTER TABLE pengiriman_beras
            MODIFY COLUMN status ENUM('menunggu','dikirim','diterima','ditolak','diproses') NOT NULL DEFAULT 'menunggu'");

        DB::statement("ALTER TABLE pengiriman_beras
            MODIFY COLUMN jenis_beras ENUM('premium','medium','setra_ramos','pandan_wangi','biasa') NOT NULL DEFAULT 'medium'");

        // 2. hasil_pengemasan: tambah '50kg' ke jenis_kemasan, ubah kualitas pakai underscore, tambah 'setra_ramos','pandan_wangi' ke jenis_beras
        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN jenis_kemasan ENUM('5kg','10kg','25kg','50kg') NOT NULL DEFAULT '5kg'");

        // Chicken-and-egg fix for 'kualitas': alter first to support both, update data, then alter to final
        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN kualitas ENUM('layak jual','layak_jual','reject') NOT NULL DEFAULT 'layak_jual'");
        DB::table('hasil_pengemasan')->where('kualitas', 'layak jual')->update(['kualitas' => 'layak_jual']);
        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN kualitas ENUM('layak_jual','reject') NOT NULL DEFAULT 'layak_jual'");

        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN jenis_beras ENUM('premium','medium','setra_ramos','pandan_wangi','biasa') NOT NULL DEFAULT 'medium'");

        // 3. pesanan: ganti jenis_produk & status menjadi string biasa agar lebih fleksibel
        DB::statement("ALTER TABLE pesanan
            MODIFY COLUMN jenis_produk VARCHAR(100) NOT NULL");

        // Chicken-and-egg fix for 'status': alter first to support both, update data, then alter to final
        DB::statement("ALTER TABLE pesanan
            MODIFY COLUMN status ENUM('pending','menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu'");
        DB::table('pesanan')->where('status', 'pending')->update(['status' => 'menunggu']);
        DB::statement("ALTER TABLE pesanan
            MODIFY COLUMN status ENUM('menunggu','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu'");

        // 4. penerimaan_beras: pastikan status punya 'menunggu'
        DB::statement("ALTER TABLE penerimaan_beras
            MODIFY COLUMN status ENUM('menunggu','diterima','ditolak','sebagian') NOT NULL DEFAULT 'menunggu'");

        // 5. penerimaan_beras: jenis_beras fleksibel
        DB::statement("ALTER TABLE penerimaan_beras
            MODIFY COLUMN jenis_beras VARCHAR(100) NOT NULL DEFAULT 'medium'");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi awal jika di-rollback
        // Update data agar tidak terjadi data truncation error 1265
        
        DB::table('pengiriman_beras')->where('status', 'dikirim')->update(['status' => 'diproses']);

        DB::statement("ALTER TABLE pengiriman_beras
            MODIFY COLUMN status ENUM('menunggu','diterima','ditolak','diproses') NOT NULL DEFAULT 'menunggu'");

        DB::table('pengiriman_beras')->whereIn('jenis_beras', ['setra_ramos', 'pandan_wangi'])->update(['jenis_beras' => 'medium']);

        DB::statement("ALTER TABLE pengiriman_beras
            MODIFY COLUMN jenis_beras ENUM('premium','medium','biasa') NOT NULL DEFAULT 'medium'");

        DB::table('hasil_pengemasan')->where('jenis_kemasan', '50kg')->update(['jenis_kemasan' => '25kg']);

        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN jenis_kemasan ENUM('5kg','10kg','25kg') NOT NULL DEFAULT '5kg'");

        // Chicken-and-egg fix for 'kualitas': alter first to support both, update data, then alter to final
        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN kualitas ENUM('layak_jual','layak jual','reject') NOT NULL DEFAULT 'layak jual'");
        DB::table('hasil_pengemasan')->where('kualitas', 'layak_jual')->update(['kualitas' => 'layak jual']);
        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN kualitas ENUM('layak jual','reject') NOT NULL DEFAULT 'layak jual'");

        DB::table('hasil_pengemasan')->whereIn('jenis_beras', ['setra_ramos', 'pandan_wangi'])->update(['jenis_beras' => 'medium']);

        DB::statement("ALTER TABLE hasil_pengemasan
            MODIFY COLUMN jenis_beras ENUM('premium','medium','biasa') NOT NULL DEFAULT 'medium'");
    }
};
