<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_tamu', 'like', "%{$search}%")
                  ->orWhere('tujuan_blok', 'like', "%{$search}%")
                  ->orWhere('no_plat', 'like', "%{$search}%")
                  ->orWhere('kode_kunjungan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe') && $request->tipe !== 'all') {
            $query->where('tipe_kunjungan', $request->tipe);
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $visitors = $query->latest('jam_checkin')->paginate(15)->withQueryString();

        $today = now()->toDateString();
        $tamuHariIni = Visitor::where('tanggal', $today)->count();
        $sedangDiDalam = Visitor::where('status', 'checkin')->count();
        $tamuMenginap = Visitor::where('status', 'menginap')->count();
        $totalSemua = Visitor::count();

        return view('visitor.index', compact(
            'visitors', 'tamuHariIni', 'sedangDiDalam', 'tamuMenginap', 'totalSemua'
        ));
    }

    public function create()
    {
        return view('visitor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_kunjungan' => 'required|in:singkat,menginap',
            'nama_tamu' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'no_plat' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'tujuan_blok' => 'required|string|max:100',
            'nama_tujuan' => 'nullable|string|max:255',
            'kepentingan' => 'required|array|min:1',
            'deskripsi_kepentingan' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'foto_dokumentasi' => 'nullable|image|max:2048',
            'tipe_foto' => 'nullable|string',
            'wa_host' => 'nullable|string|max:20',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_dokumentasi')) {
            $fotoPath = $request->file('foto_dokumentasi')->store('visitor/foto', 'public');
        }

        $now = now();
        $status = $request->tipe_kunjungan === 'menginap' ? 'menginap' : 'checkin';

        Visitor::create([
            'kode_kunjungan' => Visitor::generateKode(),
            'tipe_kunjungan' => $request->tipe_kunjungan,
            'nama_tamu' => $request->nama_tamu,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'no_plat' => $request->no_plat,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tujuan_blok' => $request->tujuan_blok,
            'nama_tujuan' => $request->nama_tujuan,
            'kepentingan' => $request->kepentingan,
            'deskripsi_kepentingan' => $request->deskripsi_kepentingan,
            'catatan_tambahan' => $request->catatan_tambahan,
            'foto_dokumentasi' => $fotoPath,
            'tipe_foto' => $request->tipe_foto,
            'wa_host' => $request->wa_host,
            'jam_checkin' => $now->format('H.i'),
            'tanggal' => $now->toDateString(),
            'status' => $status,
        ]);

        return redirect()->route('visitor.index')
            ->with('success', 'Registrasi tamu berhasil! Kode: ' . Visitor::generateKode());
    }

    public function show(Visitor $visitor)
    {
        return view('visitor.show', ['visitor' => $visitor]);
    }

    public function edit(Visitor $visitor)
    {
        return view('visitor.edit', ['visitor' => $visitor]);
    }

    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'tipe_kunjungan' => 'required|in:singkat,menginap',
            'nama_tamu' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'no_plat' => 'nullable|string|max:20',
            'jenis_kendaraan' => 'nullable|string|max:50',
            'tujuan_blok' => 'required|string|max:100',
            'nama_tujuan' => 'nullable|string|max:255',
            'kepentingan' => 'required|array|min:1',
            'deskripsi_kepentingan' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'foto_dokumentasi' => 'nullable|image|max:2048',
            'tipe_foto' => 'nullable|string',
            'wa_host' => 'nullable|string|max:20',
        ]);

        $fotoPath = $visitor->foto_dokumentasi;
        if ($request->hasFile('foto_dokumentasi')) {
            $fotoPath = $request->file('foto_dokumentasi')->store('visitor/foto', 'public');
        }

        $visitor->update([
            'tipe_kunjungan' => $request->tipe_kunjungan,
            'nama_tamu' => $request->nama_tamu,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'no_plat' => $request->no_plat,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tujuan_blok' => $request->tujuan_blok,
            'nama_tujuan' => $request->nama_tujuan,
            'kepentingan' => $request->kepentingan,
            'deskripsi_kepentingan' => $request->deskripsi_kepentingan,
            'catatan_tambahan' => $request->catatan_tambahan,
            'foto_dokumentasi' => $fotoPath,
            'tipe_foto' => $request->tipe_foto,
            'wa_host' => $request->wa_host,
        ]);

        return redirect()->route('visitor.index')
            ->with('success', 'Data kunjungan berhasil diupdate!');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitor.index')
            ->with('success', 'Data kunjungan berhasil dihapus!');
    }

    public function checkout(Visitor $visitor)
    {
        $now = now();
        $checkin = \Carbon\Carbon::parse($visitor->jam_checkin);
        $diff = $checkin->diff($now);
        
        $durasi = '';
        if ($diff->h > 0) $durasi .= $diff->h . ' jam ';
        $durasi .= $diff->i . ' menit';

        $visitor->update([
            'jam_checkout' => $now->format('H.i'),
            'durasi' => trim($durasi),
            'status' => 'checkout',
        ]);

        return redirect()->route('visitor.index')
            ->with('success', 'Checkout berhasil! Durasi: ' . $durasi);
    }
}
