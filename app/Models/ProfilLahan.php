<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfilLahan extends Model
{
    protected $fillable = [
        'user_id', 'nama_lahan', 'lokasi', 'luas_lahan',
        'jenis_tanah', 'deskripsi', 'foto', 'is_active'
    ];

    protected $casts = [
        'luas_lahan'  => 'decimal:2',
        'is_active'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function riwayatPanen(): HasMany
    {
        return $this->hasMany(RiwayatPanen::class);
    }

    // Accessor: label jenis tanah yang readable
    public function getJenisTanahLabelAttribute(): string
    {
        return match($this->jenis_tanah) {
            'tanah_liat'  => 'Tanah Liat',
            'tanah_pasir' => 'Tanah Pasir',
            'tanah_humus' => 'Tanah Humus',
            'tanah_gambut'=> 'Tanah Gambut',
            default       => 'Lainnya',
        };
    }
}
