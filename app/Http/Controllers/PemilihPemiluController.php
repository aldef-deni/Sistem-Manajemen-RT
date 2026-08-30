<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;

class PemilihPemiluController extends Controller
{
    public function index(Request $request)
    {
        // Base query: only residents aged 17+
        $query = AnggotaKeluarga::with('kartuKeluarga')
            ->whereNotNull('tanggal_lahir')
            ->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) >= 17");

        // Filter: Kelompok Umur
        if ($kelompok = $request->input('kelompok')) {
            $query->where(function ($q) use ($kelompok) {
                match ($kelompok) {
                    'pemula' => $q->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 17 AND 21"),
                    'muda' => $q->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 22 AND 35"),
                    'dewasa' => $q->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 36 AND 55"),
                    'lansia' => $q->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) >= 56"),
                    default => null,
                };
            });
        }

        // Filter: Jenis Kelamin
        if ($jk = $request->input('jenis_kelamin')) {
            $query->where('jenis_kelamin', $jk);
        }

        // Filter: Domisili
        if ($domisili = $request->input('domisili')) {
            $query->where('domisili', $domisili);
        }

        $pemilih = $query->orderBy('nama_lengkap')->paginate(10)->withQueryString();
        $totalPemilih = $pemilih->total();

        // Stats (from all eligible voters, not filtered)
        $allEligible = AnggotaKeluarga::whereNotNull('tanggal_lahir')
            ->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) >= 17");

        $totalAll = $allEligible->count();
        $laki = (clone $allEligible)->where('jenis_kelamin', 'L')->count();
        $perempuan = (clone $allEligible)->where('jenis_kelamin', 'P')->count();
        $pemula = (clone $allEligible)
            ->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) BETWEEN 17 AND 21")
            ->count();
        $lansia = (clone $allEligible)
            ->whereRaw("CAST((julianday('now') - julianday(tanggal_lahir)) / 365.25 AS INTEGER) >= 56")
            ->count();

        $totalAllWarga = AnggotaKeluarga::count();

        return view('pemilih-pemilu.index', compact(
            'pemilih', 'totalPemilih', 'totalAll', 'totalAllWarga',
            'laki', 'perempuan', 'pemula', 'lansia'
        ));
    }

    /**
     * Calculate age from date of birth
     */
    public static function hitungUmur($tanggalLahir): int
    {
        return (int) floor($tanggalLahir->diffInYears(now()));
    }

    /**
     * Get kelompok umur label
     */
    public static function getKelompok($umur): string
    {
        return match(true) {
            $umur <= 21  => 'Pemula',
            $umur <= 35  => 'Muda',
            $umur <= 55  => 'Dewasa',
            default      => 'Lansia',
        };
    }
}
