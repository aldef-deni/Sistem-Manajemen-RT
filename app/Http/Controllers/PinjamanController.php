<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\JenisPinjaman;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index()
    {
        $totalPiutang = Pinjaman::whereIn('status', ['aktif', 'disetujui'])->sum('nominal');
        $pinjamanAktif = Pinjaman::where('status', 'aktif')->count();
        $pengajuanPending = Pinjaman::where('status', 'pending')->count();
        $bayarOnlinePending = 0;

        $jadwalHariIni = Pinjaman::where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->with(['anggota', 'jenis'])
            ->get();

        return view('pinjaman.index', compact(
            'totalPiutang', 'pinjamanAktif', 'pengajuanPending', 'bayarOnlinePending', 'jadwalHariIni'
        ));
    }

    // --- Jenis Pinjaman ---
    public function jenisIndex()
    {
        $jenis = JenisPinjaman::latest()->get();
        $totalJenis = JenisPinjaman::count();
        $jenisAktif = JenisPinjaman::where('status', 'aktif')->count();
        $rataRataBunga = JenisPinjaman::where('status', 'aktif')->avg('bunga_persen') ?? 0;

        return view('pinjaman.jenis', compact('jenis', 'totalJenis', 'jenisAktif', 'rataRataBunga'));
    }

    public function jenisStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bunga_persen' => 'required|numeric|min:0',
            'denda_persen' => 'required|numeric|min:0',
            'tenor_bulan' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        JenisPinjaman::create($request->only([
            'nama', 'bunga_persen', 'denda_persen', 'tenor_bulan', 'status'
        ]));

        return redirect()->route('pinjaman.jenis')->with('success', 'Jenis pinjaman berhasil ditambahkan!');
    }

    public function jenisUpdate(Request $request, JenisPinjaman $jenisPinjaman)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'bunga_persen' => 'required|numeric|min:0',
            'denda_persen' => 'required|numeric|min:0',
            'tenor_bulan' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jenisPinjaman->update($request->only([
            'nama', 'bunga_persen', 'denda_persen', 'tenor_bulan', 'status'
        ]));

        return redirect()->route('pinjaman.jenis')->with('success', 'Jenis pinjaman berhasil diupdate!');
    }

    public function jenisDestroy(JenisPinjaman $jenisPinjaman)
    {
        $jenisPinjaman->delete();
        return redirect()->route('pinjaman.jenis')->with('success', 'Jenis pinjaman berhasil dihapus!');
    }

    // --- Pengajuan Pinjaman ---
    public function ajukan()
    {
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();
        $jenisList = JenisPinjaman::where('status', 'aktif')->get();

        return view('pinjaman.ajukan', compact('wargas', 'jenisList'));
    }

    public function storeAjukan(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_pinjaman_id' => 'required|exists:jenis_pinjaman,id',
            'nominal' => 'required|numeric|min:100000',
            'tenor_bulan' => 'required|integer|min:1',
            'keperluan' => 'required|string|min:3',
            'jaminan' => 'nullable|string',
        ]);

        $jenis = JenisPinjaman::findOrFail($request->jenis_pinjaman_id);
        $angsuran = $request->nominal / $request->tenor_bulan;

        Pinjaman::create([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'jenis_pinjaman_id' => $request->jenis_pinjaman_id,
            'nominal' => $request->nominal,
            'angsuran_per_bulan' => $angsuran,
            'tenor_bulan' => $request->tenor_bulan,
            'keperluan' => $request->keperluan,
            'jaminan' => $request->jaminan,
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil dikirim!');
    }

    public function getJenis(Request $request)
    {
        $jenis = JenisPinjaman::find($request->id);
        if (!$jenis) return response()->json(null);

        return response()->json([
            'nama' => $jenis->nama,
            'bunga' => $jenis->bunga_persen,
            'denda' => $jenis->denda_persen,
            'tenor' => $jenis->tenor_bulan,
        ]);
    }
}
