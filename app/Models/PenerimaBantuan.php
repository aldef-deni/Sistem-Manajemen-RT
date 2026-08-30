<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaBantuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penerima_bantuan';

    protected $fillable = [
        'anggota_keluarga_id',
        'nik',
        'no_kk',
        'jenis_bantuan',
        'tahun',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jenis_bantuan' => 'array',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function kartuKeluarga()
    {
        return $this->hasOneThrough(
            KartuKeluarga::class,
            AnggotaKeluarga::class,
            'id',
            'id',
            'anggota_keluarga_id',
            'kartu_keluarga_id'
        );
    }
}
