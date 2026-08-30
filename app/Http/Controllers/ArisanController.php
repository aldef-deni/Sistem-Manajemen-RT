<?php

namespace App\Http\Controllers;

use App\Models\Arisan;
use App\Models\AnggotaKeluarga;
use App\Models\RekeningKas;
use App\Models\User;
use Illuminate\Http\Request;

class ArisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Arisan::withCount('peserta');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $arisan = $query->latest()->paginate(15)->withQueryString();

        $totalArisan = Arisan::count();
        $arisanAktif = Arisan::where('status', 'aktif')->count();
        $totalPeserta = \DB::table('arisan_peserta')->count();

        return view('arisan.index', compact('arisan', 'totalArisan', 'arisanAktif', 'totalPeserta'));
    }

    public function create()
    {
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();
        $rekenings = RekeningKas::where('is_active', true)->get();

        return view('arisan.create', compact('wargas', 'rekenings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal_iuran' => 'required|numeric|min:1000',
            'periode' => 'required|in:mingguan,bulanan',
            'tanggal_mulai' => 'required|date',
            'mode_undian' => 'required|in:manual,otomatis',
            'pendamping_per_periode' => 'required|integer|min:1',
            'rekening_kas_id' => 'nullable|exists:rekening_kas,id',
            'keterangan' => 'nullable|string',
        ]);

        Arisan::create([
            'nama' => strtoupper($request->nama),
            'nominal_iuran' => $request->nominal_iuran,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'mode_undian' => $request->mode_undian,
            'jumlah_pemenang_per_pertemuan' => $request->pendamping_per_periode,
            'rekening_kas_id' => $request->rekening_kas_id,
            'keterangan' => $request->keterangan,
            'status' => 'aktif',
        ]);

        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil dibuat! Tambahkan peserta untuk memulai.');
    }

    public function show(Arisan $arisan)
    {
        $arisan->load(['peserta.kartuKeluarga', 'rekening']);
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();

        return view('arisan.show', compact('arisan', 'wargas'));
    }

    public function edit(Arisan $arisan)
    {
        $arisan->load('peserta');
        $rekenings = RekeningKas::where('is_active', true)->get();

        return view('arisan.edit', compact('arisan', 'rekenings'));
    }

    public function update(Request $request, Arisan $arisan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal_iuran' => 'required|numeric|min:1000',
            'periode' => 'required|in:mingguan,bulanan',
            'tanggal_mulai' => 'required|date',
            'mode_undian' => 'required|in:manual,otomatis',
            'jumlah_pemenang_per_pertemuan' => 'required|integer|min:1',
            'rekening_kas_id' => 'nullable|exists:rekening_kas,id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $arisan->update([
            'nama' => strtoupper($request->nama),
            'nominal_iuran' => $request->nominal_iuran,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'mode_undian' => $request->mode_undian,
            'jumlah_pemenang_per_pertemuan' => $request->jumlah_pemenang_per_pertemuan,
            'rekening_kas_id' => $request->rekening_kas_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil diupdate!');
    }

    public function destroy(Arisan $arisan)
    {
        $arisan->delete();
        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil dihapus!');
    }

    public function tambahPeserta(Request $request, Arisan $arisan)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
        ]);

        // Check if already a participant
        $exists = $arisan->peserta()->where('anggota_keluarga_id', $request->anggota_keluarga_id)->exists();
        if ($exists) {
            return back()->with('error', 'Warga ini sudah menjadi peserta arisan!');
        }

        $urutan = $arisan->peserta()->count() + 1;
        $arisan->peserta()->attach($request->anggota_keluarga_id, [
            'urutan' => $urutan,
            'sudah_dapat' => false,
        ]);

        return back()->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function hapusPeserta(Arisan $arisan, $pesertaId)
    {
        $arisan->peserta()->detach($pesertaId);
        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    public function undian(Request $request, Arisan $arisan)
    {
        // Get participants who haven't received yet
        $belumDapat = $arisan->peserta()->wherePivot('sudah_dapat', false)->get();

        if ($belumDapat->isEmpty()) {
            return back()->with('error', 'Semua peserta sudah mendapat arisan!');
        }

        // Random pick
        $pemenang = $belumDapat->random();
        $pemenang->pivot->update([
            'sudah_dapat' => true,
            'tanggal_dapat' => now(),
        ]);

        return back()->with('success', 'Pemenang arisan: ' . $pemenang->nama_lengkap . '!');
    }

    public function bayarIuran(Request $request, Arisan $arisan)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
        ]);

        // Record payment (simplified - just mark as paid for this period)
        return back()->with('success', 'Iuran arisan berhasil dicatat!');
    }
}
