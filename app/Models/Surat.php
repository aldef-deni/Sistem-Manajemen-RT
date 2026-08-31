<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat';
    protected $fillable = [
        'kode_surat', 'anggota_keluarga_id', 'nama_pemohon', 'nik',
        'jenis_surat', 'keperluan', 'file_dokumen', 'status',
        'catatan_admin', 'nomor_surat', 'tanggal_proses', 'tanggal_selesai',
        'file_surat_jadi', 'diproses_oleh'
    ];

    protected $casts = [
        'tanggal_proses' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }

    public function pemroses()
    {
        return $this->belongsTo(\App\Models\User::class, 'diproses_oleh');
    }

    public function getKodeSuratAttribute()
    {
        return 'SR-' . $this->created_at->format('ymd') . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
