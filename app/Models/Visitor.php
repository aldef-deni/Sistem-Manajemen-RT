<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'visitors';

    protected $fillable = [
        'kode_kunjungan',
        'tipe_kunjungan',
        'nama_tamu',
        'nik',
        'no_hp',
        'email',
        'no_plat',
        'jenis_kendaraan',
        'tujuan_blok',
        'nama_tujuan',
        'kepentingan',
        'deskripsi_kepentingan',
        'catatan_tambahan',
        'foto_dokumentasi',
        'tipe_foto',
        'wa_host',
        'jam_checkin',
        'jam_checkout',
        'tanggal',
        'durasi',
        'status',
    ];

    protected $casts = [
        'kepentingan' => 'array',
    ];

    public static function generateKode(): string
    {
        $now = now();
        $prefix = 'VIS-' . $now->format('ymd');
        $last = self::where('kode_kunjungan', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function hitungDurasi(): string
    {
        if (!$this->jam_checkout) return '-';
        $in = \Carbon\Carbon::parse($this->jam_checkin);
        $out = \Carbon\Carbon::parse($this->jam_checkout);
        $diff = $in->diff($out);
        if ($diff->h > 0) return $diff->h . ' jam ' . $diff->i . ' menit';
        return $diff->i . ' menit';
    }
}
