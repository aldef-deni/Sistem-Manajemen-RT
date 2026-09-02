<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengurusRT extends Model
{
    protected $table = 'pengurus_rt';
    protected $fillable = [
        'struktur_rt_id', 'nama', 'jabatan', 'foto',
        'telepon', 'email', 'alamat', 'keterangan', 'status', 'urutan',
    ];

    public function struktur()
    {
        return $this->belongsTo(StrukturRT::class, 'struktur_rt_id');
    }

    public function getInitialAttribute()
    {
        $words = explode(' ', $this->nama);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }
}
