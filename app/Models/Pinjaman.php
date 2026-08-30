<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'anggota_keluarga_id',
        'jenis_pinjaman_id',
        'nominal',
        'angsuran_per_bulan',
        'tenor_bulan',
        'keperluan',
        'jaminan',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'sisa_pinjaman',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'angsuran_per_bulan' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisPinjaman::class, 'jenis_pinjaman_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
