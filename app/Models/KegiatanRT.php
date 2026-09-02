<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanRT extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan_rt';
    protected $fillable = [
        'judul', 'artikel', 'kategori', 'status',
        'tanggal_mulai', 'tanggal_selesai', 'lokasi',
        'foto_utama', 'galeri_foto', 'dilihat', 'user_id',
    ];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'galeri_foto' => 'array',
        'dilihat' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getKategoriBadgeAttribute()
    {
        $map = [
            'Umum' => ['bg' => 'bg-slate-100 text-slate-700', 'dot' => 'bg-slate-400'],
            'Keagamaan' => ['bg' => 'bg-purple-100 text-purple-700', 'dot' => 'bg-purple-400'],
            'Kebersihan' => ['bg' => 'bg-green-100 text-green-700', 'dot' => 'bg-green-400'],
            'Keamanan' => ['bg' => 'bg-red-100 text-red-700', 'dot' => 'bg-red-400'],
            'Olahraga' => ['bg' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-400'],
            'Sosial' => ['bg' => 'bg-orange-100 text-orange-700', 'dot' => 'bg-orange-400'],
            'Lainnya' => ['bg' => 'bg-gray-100 text-gray-700', 'dot' => 'bg-gray-400'],
        ];
        return $map[$this->kategori] ?? $map['Lainnya'];
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'draft' => ['bg' => 'bg-amber-100 text-amber-700', 'label' => 'Draft'],
            'publish' => ['bg' => 'bg-green-100 text-green-700', 'label' => 'Publish'],
            'arsip' => ['bg' => 'bg-slate-100 text-slate-500', 'label' => 'Arsip'],
        ];
        return $map[$this->status] ?? $map['draft'];
    }
}
