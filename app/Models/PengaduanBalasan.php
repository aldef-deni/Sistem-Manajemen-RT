<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaduanBalasan extends Model
{
    protected $table = 'pengaduan_balasan';
    protected $fillable = ['pengaduan_id', 'user_id', 'pesan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
