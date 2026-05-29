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
            DB::statement("ALTER TABLE pengiriman_beras ALTER COLUMN bukti_kirim TYPE TEXT");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE pengiriman_beras MODIFY COLUMN bukti_kirim LONGTEXT");
        } else {
            Schema::table('pengiriman_beras', function (Blueprint $table) {
                $table->text('bukti_kirim')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // No rollback needed
    }
};
