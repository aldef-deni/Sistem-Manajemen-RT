<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KartuKeluarga extends Model
{
    protected $table = 'kartu_keluarga';
    protected $fillable = [
        'no_kk',
        'rt',
        'rw',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
    ];

    public function anggota(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class)->orderBy('id');
    }

    public function kepalaKeluarga()
    {
        return $this->hasOne(AnggotaKeluarga::class)->where('status_hubungan', 'Kepala Keluarga');
    }

    public function getKepalaNameAttribute(): string
    {
        return $this->kepalaKeluarga->nama_lengkap ?? '-';
    }

    public function getKepalaNoHpAttribute(): string
    {
        return $this->kepalaKeluarga->no_hp ?? '-';
    }

    public function getJumlahAnggotaAttribute(): int
    {
        return $this->anggota()->count();
    }
}
