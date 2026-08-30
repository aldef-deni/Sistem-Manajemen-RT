<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arisan extends Model
{
    use HasFactory;

    protected $table = 'arisan';

    protected $fillable = [
        'nama',
        'nominal_iuran',
        'periode',
        'tanggal_mulai',
        'mode_undian',
        'jumlah_pemenang_per_pertemuan',
        'rekening_kas_id',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'nominal_iuran' => 'decimal:2',
        'tanggal_mulai' => 'date',
    ];

    public function peserta()
    {
        return $this->belongsToMany(AnggotaKeluarga::class, 'arisan_peserta')
            ->withPivot('urutan', 'sudah_dapat', 'tanggal_dapat')
            ->withTimestamps();
    }

    public function rekening()
    {
        return $this->belongsTo(RekeningKas::class, 'rekening_kas_id');
    }

    public function getTotalPesertaAttribute()
    {
        return $this->peserta()->count();
    }
}
