<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotulenRapat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notulen_rapat';
    protected $fillable = [
        'judul_rapat', 'tanggal', 'waktu_mulai', 'waktu_selesai',
        'tempat', 'tim_proyek', 'moderator', 'notulis',
        'catatan', 'status', 'dilihat', 'user_id',
    ];
    protected $casts = [
        'tanggal' => 'date',
        'dilihat' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hadir()
    {
        return $this->hasMany(NotulenHadir::class);
    }

    public function poin()
    {
        return $this->hasMany(NotulenPoin::class)->orderBy('urutan');
    }

    public function getJumlahHadirAttribute()
    {
        return $this->hadir()->where('hadir', true)->count();
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'draft' => ['bg' => 'bg-amber-100 text-amber-700', 'icon' => '✎', 'label' => 'Draft'],
            'menunggu' => ['bg' => 'bg-orange-100 text-orange-700', 'icon' => '⏳', 'label' => 'Menunggu'],
            'final' => ['bg' => 'bg-emerald-100 text-emerald-700', 'icon' => '✓', 'label' => 'Final'],
        ];
        return $map[$this->status] ?? $map['draft'];
    }

    public function getTimBadgeAttribute()
    {
        $map = [
            'Keamanan' => ['bg' => 'bg-red-50 text-red-600 border-red-200'],
            'Kebersihan' => ['bg' => 'bg-green-50 text-green-600 border-green-200'],
            'Sosial' => ['bg' => 'bg-blue-50 text-blue-600 border-blue-200'],
            'Keuangan' => ['bg' => 'bg-yellow-50 text-yellow-600 border-yellow-200'],
            'Umum' => ['bg' => 'bg-slate-50 text-slate-600 border-slate-200'],
        ];
        return $map[$this->tim_proyek] ?? $map['Umum'];
    }
}
