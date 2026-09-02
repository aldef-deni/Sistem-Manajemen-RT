<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotulenPoin extends Model
{
    protected $table = 'notulen_poin';
    protected $fillable = ['notulen_rapat_id', 'topik', 'urutan'];

    public function notulen()
    {
        return $this->belongsTo(NotulenRapat::class);
    }
}
