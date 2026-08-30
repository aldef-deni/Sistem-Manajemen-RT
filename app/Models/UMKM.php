<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UMKM extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'umkm';

    protected $fillable = [
        'user_id',
        'anggota_keluarga_id',
        'nama_usaha',
        'kategori',
        'deskripsi_usaha',
        'produk_layanan',
        'alamat_lokasi',
        'jam_operasional',
        'no_telepon',
        'whatsapp',
        'instagram',
        'foto_usaha',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anggotaKeluarga()
    {
        return $this->belongsTo(AnggotaKeluarga::class);
    }
}
