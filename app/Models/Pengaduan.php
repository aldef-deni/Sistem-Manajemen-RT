<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaduan extends Model
{
    use SoftDeletes;

    protected $table = 'pengaduan';
    protected $fillable = [
        'kode_tiket', 'user_id', 'judul', 'kategori', 'isi_pengaduan',
        'privasi', 'lampiran', 'status', 'balasan', 'dibalas_oleh', 'tanggal_balas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(PengaduanBalasan::class)->orderBy('created_at');
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'diterima' => ['bg' => 'background:#dbeafe;color:#2563eb', 'label' => 'Diterima', 'icon' => '📨'],
            'diproses' => ['bg' => 'background:#fef3c7;color:#d97706', 'label' => 'Diproses', 'icon' => '🔄'],
            'selesai' => ['bg' => 'background:#dcfce7;color:#16a34a', 'label' => 'Selesai', 'icon' => '✅'],
            'ditolak' => ['bg' => 'background:#fee2e2;color:#dc2626', 'label' => 'Ditolak', 'icon' => '❌'],
        ];
        return $map[$this->status] ?? $map['diterima'];
    }

    public function getKategoriBadgeAttribute()
    {
        $map = [
            'Keamanan' => 'background:#fee2e2;color:#dc2626',
            'Kebersihan' => 'background:#dcfce7;color:#16a34a',
            'Keuangan' => 'background:#fef3c7;color:#d97706',
            'Infrastruktur' => 'background:#e0e7ff;color:#4f46e5',
            'Sosial' => 'background:#fce7f3;color:#db2777',
            'Lainnya' => 'background:#f1f5f9;color:#64748b',
        ];
        return $map[$this->kategori] ?? $map['Lainnya'];
    }
}
