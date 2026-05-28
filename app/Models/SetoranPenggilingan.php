<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranPenggilingan extends Model
{
    // Tambahkan baris ini ↓
    protected $table = 'setoran_penggilingan';

    protected $fillable = [
        'user_id', 'tanggal_setoran', 'jenis_hasil_panen', 'jumlah_setoran',
        'biaya_penggilingan', 'hasil_bersih', 'total_pendapatan',
        'bukti_nota', 'catatan', 'status'
    ];

    protected $casts = [
        'tanggal_setoran'    => 'date',
        'jumlah_setoran'     => 'decimal:2',
        'biaya_penggilingan' => 'decimal:2',
        'hasil_bersih'       => 'decimal:2',
        'total_pendapatan'   => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'warning',
            'diproses' => 'info',
            'selesai'  => 'success',
            default    => 'secondary',
        };
    }
}