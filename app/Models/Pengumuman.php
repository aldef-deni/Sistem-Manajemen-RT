<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengumuman';
    protected $fillable = [
        'judul', 'kategori', 'target', 'isi', 'tanggal_publish',
        'tanggal_berakhir', 'lampiran', 'status', 'dilihat', 'dibuat_oleh'
    ];

    protected $casts = [
        'tanggal_publish' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(\App\Models\User::class, 'dibuat_oleh');
    }

    public function getIsPublishAttribute()
    {
        return $this->status === 'publish';
    }

    public function getIsExpiredAttribute()
    {
        return $this->tanggal_berakhir && $this->tanggal_berakhir->isPast();
    }
}
