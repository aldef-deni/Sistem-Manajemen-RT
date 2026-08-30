<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'kondisi',
        'jumlah',
        'satuan',
        'lokasi',
        'tanggal_pembelian',
        'harga_pembelian',
        'sumber_dana',
        'keterangan',
        'foto_utama',
        'foto_gallery',
        'status',
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'harga_pembelian' => 'decimal:2',
        'foto_gallery' => 'array',
    ];

    public function rencanaPembelian()
    {
        return $this->hasMany(RencanaPembelian::class);
    }

    public function getKondisiBadgeAttribute()
    {
        return match($this->kondisi) {
            'Baik' => ['color' => 'green', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
            'Rusak Ringan' => ['color' => 'yellow', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
            'Rusak Berat' => ['color' => 'red', 'bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200'],
            'Perlu Perbaikan' => ['color' => 'orange', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
            default => ['color' => 'gray', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
        };
    }

    public function getKategoriBadgeAttribute()
    {
        return match($this->kategori) {
            'Elektronik' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => '💻'],
            'Perlengkapan' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'icon' => '🔧'],
            'Furniture' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => '🪑'],
            'ATK' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => '📎'],
            default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'icon' => '📦'],
        };
    }
}
