<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    use HasFactory;

    protected $table = 'transaksi_kas';

    protected $fillable = [
        'tanggal',
        'jenis',
        'kategori',
        'rekening_kas_id',
        'nominal',
        'keterangan',
        'bukti_dokumen',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function rekening()
    {
        return $this->belongsTo(RekeningKas::class, 'rekening_kas_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
