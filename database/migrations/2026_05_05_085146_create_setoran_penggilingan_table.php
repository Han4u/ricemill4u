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
        Schema::create('setoran_penggilingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_setoran');
            $table->string('jenis_hasil_panen'); 
            $table->decimal('jumlah_setoran', 10, 2); 
            $table->decimal('biaya_penggilingan', 10, 2)->nullable();
            $table->decimal('hasil_bersih', 10, 2)->nullable(); 
            $table->decimal('total_pendapatan', 15, 2)->nullable();
            $table->string('bukti_nota')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_penggilingan');
    }
};
