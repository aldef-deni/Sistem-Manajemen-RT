<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang';

    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'jumlah_pinjam',
        'kondisi_saat_pinjam',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'tanggal_kembali',
        'keperluan',
        'nama_peminjam',
        'no_hp_peminjam',
        'anggota_keluarga_id',
        'status',
        'kondisi_saat_kembali',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_kembali' => 'date',
        'jumlah_pinjam' => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'dipinjam' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => '📦'],
            'dikembalikan' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => '✅'],
            'terlambat' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => '⏰'],
            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'icon' => '📋'],
        };
    }
}
