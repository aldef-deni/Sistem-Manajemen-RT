<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisIuran extends Model
{
    use HasFactory;

    protected $table = 'jenis_iuran';

    protected $fillable = [
        'nama',
        'nominal_default',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'nominal_default' => 'integer',
        'is_active' => 'boolean',
    ];

    public function iuranWarga()
    {
        return $this->hasMany(IuranWarga::class);
    }
}
