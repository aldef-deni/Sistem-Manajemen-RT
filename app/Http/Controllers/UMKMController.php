<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UMKMController extends Controller
{
    public function index(Request $request)
    {
        $query = UMKM::with(['user', 'anggotaKeluarga']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('deskripsi_usaha', 'like', "%{$search}%")
                  ->orWhere('alamat_lokasi', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $umkms = $query->latest()->paginate(12);

        // Stats
        $totalUmk = UMKM::count();
        $aktif = UMKM::where('status', 'aktif')->count();
        $pending = UMKM::where('status', 'pending_review')->count();
        $kategoriCount = UMKM::distinct('kategori')->count();

        return view('umkm.index', compact('umkms', 'totalUmk', 'aktif', 'pending', 'kategoriCount'));
    }

    public function create()
    {
        return view('umkm.create');
    }

    public function daftarkan()
    {
        return view('umkm.daftarkan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi_usaha' => 'required|string|max:1000',
            'produk_layanan' => 'nullable|string|max:500',
            'alamat_lokasi' => 'nullable|string|max:500',
            'jam_operasional' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'foto_usaha' => 'nullable|image|max:2048',
            'status' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'aktif';

        if ($request->hasFile('foto_usaha')) {
            $validated['foto_usaha'] = $request->file('foto_usaha')->store('umkm', 'public');
        }

        UMKM::create($validated);

        return redirect()->route('umkm.index')->with('success', 'UMKM berhasil ditambahkan!');
    }

    public function storeDaftarkan(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi_usaha' => 'required|string|max:1000',
            'produk_layanan' => 'nullable|string|max:500',
            'alamat_lokasi' => 'nullable|string|max:500',
            'jam_operasional' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'foto_usaha' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending_review';

        if ($request->hasFile('foto_usaha')) {
            $validated['foto_usaha'] = $request->file('foto_usaha')->store('umkm', 'public');
        }

        UMKM::create($validated);

        return redirect()->route('umkm.index')->with('success', 'Usaha berhasil didaftarkan! Menunggu review dari admin.');
    }

    public function edit(UMKM $umkm)
    {
        return view('umkm.edit', compact('umkm'));
    }

    public function update(Request $request, UMKM $umkm)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi_usaha' => 'required|string|max:1000',
            'produk_layanan' => 'nullable|string|max:500',
            'alamat_lokasi' => 'nullable|string|max:500',
            'jam_operasional' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'foto_usaha' => 'nullable|image|max:2048',
            'status' => 'nullable|string',
        ]);

        if ($request->hasFile('foto_usaha')) {
            $validated['foto_usaha'] = $request->file('foto_usaha')->store('umkm', 'public');
        }

        $umkm->update($validated);

        return redirect()->route('umkm.index')->with('success', 'UMKM berhasil diupdate!');
    }

    public function destroy(UMKM $umkm)
    {
        $umkm->delete();
        return redirect()->route('umkm.index')->with('success', 'UMKM berhasil dihapus!');
    }
}
