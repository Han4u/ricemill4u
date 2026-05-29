<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        if ($isPgsql) {
            DB::statement("ALTER TABLE keuangan_ricemill ALTER COLUMN kategori TYPE VARCHAR(100) USING kategori::varchar");
            DB::statement("ALTER TABLE keuangan_ricemill ALTER COLUMN kategori SET DEFAULT 'lainnya'");
        } else {
            DB::statement("ALTER TABLE keuangan_ricemill MODIFY COLUMN kategori VARCHAR(100) NOT NULL DEFAULT 'lainnya'");
        }
    }

    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        if ($isPgsql) {
            DB::statement("ALTER TABLE keuangan_ricemill ALTER COLUMN kategori SET DEFAULT 'lainnya'");
        } else {
            DB::statement("ALTER TABLE keuangan_ricemill MODIFY COLUMN kategori ENUM('operasional','tenaga_kerja','setoran','penggilingan','pengiriman','lainnya') NOT NULL DEFAULT 'lainnya'");
        }
    }
};
