<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Arisan extends Model
{
    use HasFactory;

    protected $table = 'arisan';

    protected $fillable = [
        'nama',
        'nominal_iuran',
        'periode',
        'tanggal_mulai',
        'mode_undian',
        'jumlah_pemenang_per_pertemuan',
        'rekening_kas_id',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'nominal_iuran' => 'decimal:2',
        'tanggal_mulai' => 'date',
    ];

    public function peserta()
    {
        return $this->belongsToMany(AnggotaKeluarga::class, 'arisan_peserta')
            ->withPivot('urutan', 'sudah_dapat', 'tanggal_dapat')
            ->withTimestamps();
    }

    public function rekening()
    {
        return $this->belongsTo(RekeningKas::class, 'rekening_kas_id');
    }

    public function iuran(): HasMany
    {
        return $this->hasMany(ArisanIuran::class);
    }

    public function getTotalPesertaAttribute()
    {
        return $this->peserta()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Periode
    |--------------------------------------------------------------------------
    | Periode ke-1 dimulai pada tanggal_mulai. Periode berikutnya maju satu
    | minggu atau satu bulan, tergantung setelan arisan.
    */

    public function tanggalPeriode(int $ke): Carbon
    {
        $ke    = max(1, $ke);
        $mulai = ($this->tanggal_mulai ?? now())->copy()->startOfDay();

        return $this->periode === 'mingguan'
            ? $mulai->addWeeks($ke - 1)
            : $mulai->addMonths($ke - 1);
    }

    /** Periode yang sedang berjalan hari ini. */
    public function periodeSaatIni(): int
    {
        $mulai = ($this->tanggal_mulai ?? now())->copy()->startOfDay();
        $kini  = now()->startOfDay();

        if ($kini->lessThan($mulai)) {
            return 1;
        }

        $lewat = $this->periode === 'mingguan'
            ? (int) $mulai->diffInWeeks($kini)
            : (int) $mulai->diffInMonths($kini);

        return min($lewat + 1, max(1, $this->jumlahPeriode()));
    }

    /**
     * Banyaknya periode sampai seluruh peserta kebagian giliran.
     * Nol peserta berarti arisan belum bisa berjalan.
     */
    public function jumlahPeriode(): int
    {
        $peserta  = $this->peserta()->count();
        $pemenang = max(1, (int) ($this->jumlah_pemenang_per_pertemuan ?: 1));

        return $peserta === 0 ? 0 : (int) ceil($peserta / $pemenang);
    }

    public function labelPeriode(int $ke): string
    {
        $tanggal = $this->tanggalPeriode($ke);

        return $this->periode === 'mingguan'
            ? 'Minggu ke-' . $ke . ' · ' . $tanggal->translatedFormat('d M Y')
            : 'Bulan ke-' . $ke . ' · ' . $tanggal->translatedFormat('F Y');
    }

    /** Total uang iuran yang sudah tercatat masuk. */
    public function totalTerkumpul(): float
    {
        return (float) $this->iuran()->sum('nominal');
    }

    /** Yang seharusnya terkumpul bila semua peserta membayar tiap periode. */
    public function targetTerkumpul(): float
    {
        return $this->peserta()->count() * $this->jumlahPeriode() * (float) $this->nominal_iuran;
    }
}
