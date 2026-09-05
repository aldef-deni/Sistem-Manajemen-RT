<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArisanIuran extends Model
{
    protected $table = 'arisan_iuran';

    protected $fillable = [
        'arisan_id',
        'anggota_keluarga_id',
        'periode_ke',
        'nominal',
        'tanggal_bayar',
        'metode',
        'keterangan',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'nominal'       => 'decimal:2',
            'tanggal_bayar' => 'date',
            'periode_ke'    => 'integer',
        ];
    }

    public function arisan(): BelongsTo
    {
        return $this->belongsTo(Arisan::class);
    }

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
