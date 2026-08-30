<?php

namespace App\Models;

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

    public function kartuKeluarga(): BelongsTo
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    public function tabungan()
    {
        return $this->hasOne(Tabungan::class);
    }
}
