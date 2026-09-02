<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotulenHadir extends Model
{
    protected $table = 'notulen_hadir';
    protected $fillable = ['notulen_rapat_id', 'nama_peserta', 'ulasan', 'hadir'];
    protected $casts = ['hadir' => 'boolean'];

    public function notulen()
    {
        return $this->belongsTo(NotulenRapat::class);
    }
}
