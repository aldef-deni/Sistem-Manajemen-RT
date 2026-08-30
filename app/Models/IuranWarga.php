<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranWarga extends Model
{
    use HasFactory;

    protected $table = 'iuran_warga';

    protected $fillable = [
        'anggota_keluarga_id',
        'jenis_iuran_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'tanggal_bayar',
        'catatan',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'tanggal_bayar' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function jenisIuran()
    {
        return $this->belongsTo(JenisIuran::class);
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulan[$this->bulan] ?? '-';
    }

    public function getPeriodeAttribute(): string
    {
        return $this->nama_bulan . ' ' . $this->tahun;
    }

    public function getNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
