<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabunganTransaksi extends Model
{
    use HasFactory;

    protected $table = 'tabungan_transaksi';

    protected $fillable = [
        'tabungan_id',
        'jenis',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'rekening_kas_id',
        'keterangan',
        'status',
        'user_id',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }

    public function rekening()
    {
        return $this->belongsTo(RekeningKas::class, 'rekening_kas_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
