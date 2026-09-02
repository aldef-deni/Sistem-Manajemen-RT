<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturRT extends Model
{
    protected $table = 'struktur_rt';
    protected $fillable = [
        'nama_rt', 'nomor_rt', 'nomor_rw', 'alamat_rt',
        'kelurahan', 'kecamatan', 'kota', 'provinsi', 'kode_pos',
        'telepon_rt', 'email_rt', 'logo_rt', 'visi', 'misi', 'peraturan',
    ];

    public function pengurus()
    {
        return $this->hasMany(PengurusRT::class, 'struktur_rt_id')->orderBy('urutan');
    }

    public function pengurusAktif()
    {
        return $this->pengurus()->where('status', 'aktif');
    }
}
