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
        'file_kk',
    ];

    public function getFileKkUrlAttribute(): ?string
    {
        return $this->file_kk && file_exists(public_path($this->file_kk))
            ? asset($this->file_kk)
            : null;
    }

    public function getFileKkIsImageAttribute(): bool
    {
        return $this->file_kk && in_array(
            strtolower(pathinfo($this->file_kk, PATHINFO_EXTENSION)),
            ['jpg', 'jpeg', 'png', 'webp'],
            true
        );
    }

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
