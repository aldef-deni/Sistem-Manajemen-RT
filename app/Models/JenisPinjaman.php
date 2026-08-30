<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPinjaman extends Model
{
    use HasFactory;

    protected $table = 'jenis_pinjaman';

    protected $fillable = [
        'nama',
        'bunga_persen',
        'denda_persen',
        'tenor_bulan',
        'status',
    ];

    protected $casts = [
        'bunga_persen' => 'decimal:2',
        'denda_persen' => 'decimal:2',
        'tenor_bulan' => 'integer',
    ];

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }
}
