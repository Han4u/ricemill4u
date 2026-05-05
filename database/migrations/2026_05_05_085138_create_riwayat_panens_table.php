<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_panens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('profil_lahan_id')->constrained('profil_lahans')->onDelete('cascade');
            $table->date('tanggal_panen');
            $table->string('jenis_tanaman');
            $table->decimal('jumlah_hasil', 10, 2);     
            $table->string('satuan')->default('kg');
            $table->decimal('harga_per_kg', 10, 2)->nullable();
            $table->decimal('total_pendapatan', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_panens');
    }
};
