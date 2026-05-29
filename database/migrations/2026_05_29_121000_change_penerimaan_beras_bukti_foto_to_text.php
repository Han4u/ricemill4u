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
            DB::statement("ALTER TABLE penerimaan_beras ALTER COLUMN bukti_foto TYPE TEXT");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE penerimaan_beras MODIFY COLUMN bukti_foto LONGTEXT");
        } else {
            Schema::table('penerimaan_beras', function (Blueprint $table) {
                $table->text('bukti_foto')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};
