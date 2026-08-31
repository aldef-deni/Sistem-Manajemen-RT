<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalKegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_kegiatan';
    protected $fillable = [
        'nama_kegiatan', 'kategori', 'jenis_jadwal', 'lokasi',
        'penanggung_jawab_id', 'deskripsi', 'petugas',
        'tanggal_mulai', 'jam_mulai', 'jam_selesai', 'tanggal_selesai',
        'status', 'dibuat_oleh'
    ];

    protected $casts = [
        'petugas' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function penanggungJawab()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'penanggung_jawab_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(\App\Models\User::class, 'dibuat_oleh');
    }

    public function getIsTodayAttribute()
    {
        return $this->tanggal_mulai && $this->tanggal_mulai->isToday();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->tanggal_mulai && $this->tanggal_mulai->isFuture();
    }
}
