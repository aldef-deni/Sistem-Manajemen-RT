<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;

class PemilihPemiluController extends Controller
{
    public function index(Request $request)
    {
        // Base query: only residents aged 17+
        $query = AnggotaKeluarga::with('kartuKeluarga')->usiaAntara(17);

        // Filter: Kelompok Umur
        if ($kelompok = $request->input('kelompok')) {
            $query->where(function ($q) use ($kelompok) {
                match ($kelompok) {
                    'pemula' => $q->usiaAntara(17, 21),
                    'muda'   => $q->usiaAntara(22, 35),
                    'dewasa' => $q->usiaAntara(36, 55),
                    'lansia' => $q->usiaAntara(56),
                    default  => null,
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
        $allEligible = AnggotaKeluarga::usiaAntara(17);

        $totalAll = $allEligible->count();
        $laki = (clone $allEligible)->where('jenis_kelamin', 'L')->count();
        $perempuan = (clone $allEligible)->where('jenis_kelamin', 'P')->count();
        $pemula = (clone $allEligible)->usiaAntara(17, 21)->count();
        $lansia = (clone $allEligible)->usiaAntara(56)->count();

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
