<?php

namespace App\Http\Controllers;

use App\Models\KegiatanRT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanRTController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanRT::with('user');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $kegiatans = $query->latest('tanggal_mulai')->paginate(12)->withQueryString();

        return view('kegiatan-rt.index', compact('kegiatans'));
    }

    public function create()
    {
        return view('kegiatan-rt.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'artikel' => 'required|string',
            'kategori' => 'required|string',
            'status' => 'required|in:draft,publish',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:200',
            'foto_utama' => 'nullable|image|max:3072',
            'galeri_foto.*' => 'nullable|image|max:5120',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto_utama')) {
            $validated['foto_utama'] = $request->file('foto_utama')->store('kegiatan', 'public');
        }

        $galeri = [];
        if ($request->hasFile('galeri_foto')) {
            foreach ($request->file('galeri_foto') as $file) {
                $galeri[] = $file->store('kegiatan/galeri', 'public');
            }
        }
        $validated['galeri_foto'] = $galeri;

        KegiatanRT::create($validated);

        return redirect()->route('kegiatan-rt.index')->with('success', 'Kegiatan berhasil dibuat!');
    }

    public function show(KegiatanRT $kegiatanRt)
    {
        $kegiatanRt->increment('dilihat');
        $kegiatanRt->load('user');
        return view('kegiatan-rt.show', ['kegiatan' => $kegiatanRt]);
    }

    public function edit(KegiatanRT $kegiatanRt)
    {
        return view('kegiatan-rt.edit', ['kegiatan' => $kegiatanRt]);
    }

    public function update(Request $request, KegiatanRT $kegiatanRt)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'artikel' => 'required|string',
            'kategori' => 'required|string',
            'status' => 'required|in:draft,publish,arsip',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'nullable|string|max:200',
            'foto_utama' => 'nullable|image|max:3072',
            'galeri_foto.*' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('foto_utama')) {
            if ($kegiatanRt->foto_utama) {
                Storage::disk('public')->delete($kegiatanRt->foto_utama);
            }
            $validated['foto_utama'] = $request->file('foto_utama')->store('kegiatan', 'public');
        }

        if ($request->hasFile('galeri_foto')) {
            $galeri = $kegiatanRt->galeri_foto ?? [];
            foreach ($request->file('galeri_foto') as $file) {
                $galeri[] = $file->store('kegiatan/galeri', 'public');
            }
            $validated['galeri_foto'] = $galeri;
        }

        $kegiatanRt->update($validated);

        return redirect()->route('kegiatan-rt.show', $kegiatanRt)->with('success', 'Kegiatan berhasil diupdate!');
    }

    public function destroy(KegiatanRT $kegiatanRt)
    {
        if ($kegiatanRt->foto_utama) {
            Storage::disk('public')->delete($kegiatanRt->foto_utama);
        }
        $kegiatanRt->delete();

        return redirect()->route('kegiatan-rt.index')->with('success', 'Kegiatan berhasil dihapus!');
    }
}
