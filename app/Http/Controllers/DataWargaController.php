<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;

class DataWargaController extends Controller
{
    public function index(Request $request)
    {
        $query = AnggotaKeluarga::with('kartuKeluarga');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhereHas('kartuKeluarga', function ($q2) use ($search) {
                      $q2->where('no_kk', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->input('sort', 'nama_lengkap');
        $sortDir = $request->input('dir', 'asc');
        $allowedSorts = ['nama_lengkap', 'nik', 'status_hubungan', 'domisili', 'created_at'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('nama_lengkap', 'asc');
        }

        $perPage = $request->input('per_page', 10);
        $wargas = $query->paginate($perPage)->withQueryString();
        $totalWarga = AnggotaKeluarga::count();
        $totalKK = KartuKeluarga::count();

        // Gender stats
        $lakiLaki = AnggotaKeluarga::where('jenis_kelamin', 'L')->count();
        $perempuan = AnggotaKeluarga::where('jenis_kelamin', 'P')->count();

        // Age distribution
        $now = now();
        $balita = AnggotaKeluarga::usiaAntara(0, 4)->count();
        $anak   = AnggotaKeluarga::usiaAntara(5, 12)->count();
        $remaja = AnggotaKeluarga::usiaAntara(13, 17)->count();
        $pemuda = AnggotaKeluarga::usiaAntara(18, 59)->count();
        $lansia = AnggotaKeluarga::usiaAntara(60)->count();

        // Status distribution
        $janda = AnggotaKeluarga::where('status_hubungan', 'Janda')->count();
        $duda = AnggotaKeluarga::where('status_hubungan', 'Duda')->count();

        return view('data-warga.index', compact(
            'wargas', 'totalWarga', 'totalKK',
            'lakiLaki', 'perempuan',
            'balita', 'anak', 'remaja', 'pemuda', 'lansia',
            'janda', 'duda'
        ));
    }
}
