<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPembelian extends Model
{
    use HasFactory;

    protected $table = 'rencana_pembelian';

    protected $fillable = [
        'kode_rencana',
        'nama_barang',
        'kategori',
        'jumlah',
        'satuan',
        'prioritas',
        'estimasi_harga',
        'sumber_dana',
        'tanggal_rencana',
        'keterangan',
        'status',
        'barang_id',
    ];

    protected $casts = [
        'tanggal_rencana' => 'date',
        'estimasi_harga' => 'decimal:2',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function getPrioritasBadgeAttribute()
    {
        return match($this->prioritas) {
            'tinggi' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Tinggi'],
            'sedang' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'Sedang'],
            'rendah' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Rendah'],
            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'label' => ucfirst($this->prioritas)],
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'direncanakan' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => '📋'],
            'disetujui' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => '⏳'],
            'terbeli' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => '✅'],
            'hibah' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => '🎁'],
            'dibatalkan' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => '❌'],
            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'icon' => '📋'],
        };
    }
}
