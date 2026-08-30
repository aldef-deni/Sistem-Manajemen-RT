<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanKurangMampu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengajuan_kurang_mampu';

    protected $fillable = [
        'anggota_keluarga_id',
        'nik',
        'no_kk',
        'penghasilan_per_bulan',
        'pekerjaan',
        'jumlah_tanggungan',
        'status_rumah',
        'kondisi_rumah',
        'alasan_pengajuan',
        'keterangan',
        'foto_rumah',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'penghasilan_per_bulan' => 'decimal:2',
        'jumlah_tanggungan' => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }
}
