<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningKas extends Model
{
    use HasFactory;

    protected $table = 'rekening_kas';

    protected $fillable = [
        'nama',
        'jenis',
        'saldo',
        'is_active',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiKas::class);
    }
}
