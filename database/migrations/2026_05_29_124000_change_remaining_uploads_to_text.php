<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE penerimaan_gabah ALTER COLUMN bukti_foto TYPE TEXT");
            DB::statement("ALTER TABLE setoran_penggilingan ALTER COLUMN bukti_nota TYPE TEXT");
            DB::statement("ALTER TABLE riwayat_panens ALTER COLUMN bukti_foto TYPE TEXT");
            DB::statement("ALTER TABLE profil_lahans ALTER COLUMN foto TYPE TEXT");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE penerimaan_gabah MODIFY COLUMN bukti_foto LONGTEXT");
            DB::statement("ALTER TABLE setoran_penggilingan MODIFY COLUMN bukti_nota LONGTEXT");
            DB::statement("ALTER TABLE riwayat_panens MODIFY COLUMN bukti_foto LONGTEXT");
            DB::statement("ALTER TABLE profil_lahans MODIFY COLUMN foto LONGTEXT");
        } else {
            Schema::table('penerimaan_gabah', function (Blueprint $table) {
                $table->text('bukti_foto')->nullable()->change();
            });
            Schema::table('setoran_penggilingan', function (Blueprint $table) {
                $table->text('bukti_nota')->nullable()->change();
            });
            Schema::table('riwayat_panens', function (Blueprint $table) {
                $table->text('bukti_foto')->nullable()->change();
            });
            Schema::table('profil_lahans', function (Blueprint $table) {
                $table->text('foto')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};
