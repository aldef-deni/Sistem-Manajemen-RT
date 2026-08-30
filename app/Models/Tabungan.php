<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    protected $table = 'tabungan';

    protected $fillable = [
        'anggota_keluarga_id',
        'no_rekening',
        'jenis_tabungan',
        'saldo',
        'status',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TabunganTransaksi::class)->latest();
    }

    public function getNamaLengkapAttribute()
    {
        return $this->anggota->nama_lengkap ?? '-';
    }

    public function getNoHpAttribute()
    {
        return $this->anggota->no_hp ?? '-';
    }
}
