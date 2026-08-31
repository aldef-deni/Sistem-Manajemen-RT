<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::with('pembuat');

        if ($kategori = $request->kategori) {
            $query->where('kategori', $kategori);
        }

        if ($bulan = $request->bulan) {
            $query->whereMonth('tanggal_publish', $bulan);
        }

        if ($tahun = $request->tahun) {
            $query->whereYear('tanggal_publish', $tahun);
        }

        $pengumuman = $query->latest('tanggal_publish')->paginate(12)->withQueryString();

        $kategoriList = ['Umum', 'Keuangan', 'Keamanan', 'Kebersihan', 'Kegiatan', 'Darurat', 'Lainnya'];

        return view('pengumuman.index', compact('pengumuman', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = ['Umum', 'Keuangan', 'Keamanan', 'Kebersihan', 'Kegiatan', 'Darurat', 'Lainnya'];
        $targetList = [
            'semua' => 'Semua Warga',
            'rt' => 'Pengurus RT',
            'rw' => 'Pengurus RW',
            'per_blok' => 'Per Blok',
            'warga_tertentu' => 'Warga Tertentu',
        ];

        return view('pengumuman.create', compact('kategoriList', 'targetList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'target' => 'required|string|max:50',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_publish',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:draft,publish',
        ]);

        $validated['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-pengumuman', 'public');
        }

        Pengumuman::create($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::with('pembuat')->findOrFail($id);
        $pengumuman->increment('dilihat');

        return view('pengumuman.show', compact('pengumuman'));
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $kategoriList = ['Umum', 'Keuangan', 'Keamanan', 'Kebersihan', 'Kegiatan', 'Darurat', 'Lainnya'];
        $targetList = [
            'semua' => 'Semua Warga',
            'rt' => 'Pengurus RT',
            'rw' => 'Pengurus RW',
            'per_blok' => 'Per Blok',
            'warga_tertentu' => 'Warga Tertentu',
        ];

        return view('pengumuman.edit', compact('pengumuman', 'kategoriList', 'targetList'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:50',
            'target' => 'required|string|max:50',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'tanggal_berakhir' => 'nullable|date',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:draft,publish',
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-pengumuman', 'public');
        }

        $pengumuman->update($validated);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
