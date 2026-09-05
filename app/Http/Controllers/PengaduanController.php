<?php

namespace App\Http\Controllers;

use App\Support\SafeUpload;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user', 'replies']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_tiket', 'like', "%{$s}%")
                  ->orWhere('judul', 'like', "%{$s}%")
                  ->orWhere('kategori', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengaduans = $query->latest()->paginate(25)->withQueryString();

        return view('pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pengaduan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'isi_pengaduan' => 'required|string',
            'privasi' => 'required|in:publik,privat',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['kode_tiket'] = $this->generateKodeTiket();

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = SafeUpload::store(
                $request->file('lampiran'),
                'pengaduan',
                'lampiran',
                SafeUpload::DOCUMENT
            );
        }

        Pengaduan::create($validated);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dikirim! Kode tiket: ' . $validated['kode_tiket']);
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'replies.user']);

        return view('pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,ditolak',
        ]);

        $pengaduan->update($validated);

        return redirect()->route('pengaduan.show', $pengaduan)->with('success', 'Status pengaduan berhasil diupdate!');
    }

    public function balas(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'pesan' => 'required|string',
        ]);

        PengaduanBalasan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => Auth::id(),
            'pesan' => $validated['pesan'],
        ]);

        $pengaduan->update([
            'balasan' => $validated['pesan'],
            'dibalas_oleh' => Auth::user()->name,
            'tanggal_balas' => now(),
        ]);

        return redirect()->route('pengaduan.show', $pengaduan)->with('success', 'Balasan berhasil dikirim!');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();
        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus!');
    }

    private function generateKodeTiket()
    {
        $date = now()->format('ymd');
        $last = Pengaduan::where('kode_tiket', 'like', "TKT{$date}%")->count() + 1;
        return 'TKT' . $date . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
