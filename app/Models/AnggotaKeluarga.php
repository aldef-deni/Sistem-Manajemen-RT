<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKeluarga extends Model
{
    protected $table = 'anggota_keluarga';
    protected $fillable = [
        'kartu_keluarga_id',
        'nik',
        'nama_lengkap',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'status_hubungan',
        'status_kawin',
        'domisili',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Saring anggota berdasarkan rentang umur, batas bawah dan atas ikut
     * dihitung: usiaAntara(17, 21) berarti 17 sampai 21 tahun.
     *
     * Ditulis sebagai rentang tanggal lahir, bukan lewat fungsi tanggal
     * bawaan database, supaya kueri yang sama berjalan di SQLite (lokal)
     * dan MySQL (produksi).
     */
    public function scopeUsiaAntara(Builder $query, ?int $min = null, ?int $max = null): Builder
    {
        $query->whereNotNull('tanggal_lahir');

        if ($min !== null) {
            // Umur >= $min berarti lahir paling lambat $min tahun yang lalu.
            $query->whereDate('tanggal_lahir', '<=', now()->subYears($min)->toDateString());
        }

        if ($max !== null) {
            // Umur <= $max berarti lahir setelah ulang tahun ke-($max+1).
            $query->whereDate('tanggal_lahir', '>', now()->subYears($max + 1)->toDateString());
        }

        return $query;
    }

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function tabungan()
    {
        return $this->hasOne(Tabungan::class);
    }
}
