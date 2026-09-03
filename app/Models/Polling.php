<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Polling extends Model
{
    use SoftDeletes;

    protected $table = 'polling';
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai',
        'opsi', 'tampilkan_hasil', 'izinkan_ganti', 'anonim', 'status', 'jumlah_suara',
    ];

    protected $casts = [
        'opsi' => 'array',
        'tampilkan_hasil' => 'boolean',
        'izinkan_ganti' => 'boolean',
        'anonim' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(PollingVote::class);
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'aktif' => ['bg' => 'background:#dcfce7;color:#16a34a', 'label' => 'Aktif', 'icon' => '🟢'],
            'selesai' => ['bg' => 'background:#dbeafe;color:#2563eb', 'label' => 'Selesai', 'icon' => '🔵'],
            'ditutup' => ['bg' => 'background:#f1f5f9;color:#64748b', 'label' => 'Ditutup', 'icon' => '🔒'],
        ];
        return $map[$this->status] ?? $map['aktif'];
    }

    public function getResults()
    {
        $total = $this->votes()->count();
        $results = [];
        foreach ($this->opsi as $op) {
            $count = $this->votes()->where('pilihan', $op)->count();
            $results[$op] = [
                'count' => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }
        return $results;
    }

    public function userVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }
}
