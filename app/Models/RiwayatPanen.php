<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPanen extends Model
{
    protected $fillable = [
        'user_id', 'profil_lahan_id', 'tanggal_panen', 'jenis_tanaman',
        'jumlah_hasil', 'satuan', 'harga_per_kg', 'total_pendapatan',
        'catatan', 'bukti_foto'
    ];

    protected $casts = [
        'tanggal_panen'    => 'date',
        'jumlah_hasil'     => 'decimal:2',
        'harga_per_kg'     => 'decimal:2',
        'total_pendapatan' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profilLahan(): BelongsTo
    {
        return $this->belongsTo(ProfilLahan::class);
    }
    protected static function booted(): void
    {
        static::saving(function (RiwayatPanen $panen) {
            if ($panen->jumlah_hasil && $panen->harga_per_kg) {
                $panen->total_pendapatan = $panen->jumlah_hasil * $panen->harga_per_kg;
            }
        });
    }
}