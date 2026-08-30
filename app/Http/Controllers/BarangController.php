<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::where('status', '!=', 'dihapus');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barangs = $query->latest()->paginate(12)->withQueryString();

        // Stats
        $totalAset = Barang::where('status', '!=', 'dihapus')->count();
        $kondisiBaik = Barang::where('kondisi', 'Baik')->where('status', '!=', 'dihapus')->count();
        $perluPerbaikan = Barang::where('kondisi', 'Perlu Perbaikan')->where('status', '!=', 'dihapus')->count();
        $totalNilai = Barang::where('status', '!=', 'dihapus')->sum('harga_pembelian');

        $kategoriList = Barang::where('status', '!=', 'dihapus')->distinct()->pluck('kategori');
        $kondisiList = Barang::where('status', '!=', 'dihapus')->distinct()->pluck('kondisi');

        return view('barang.index', compact('barangs', 'totalAset', 'kondisiBaik', 'perluPerbaikan', 'totalNilai', 'kategoriList', 'kondisiList'));
    }

    public function create()
    {
        $lastKode = Barang::latest('id')->first();
        $nextNumber = $lastKode ? intval(substr($lastKode->kode_barang, -3)) + 1 : 1;
        $kode = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('barang.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:Elektronik,Perlengkapan,Furniture,ATK,Lainnya',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Perlu Perbaikan',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'foto_utama' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'kode_barang', 'nama_barang', 'kategori', 'kondisi', 'jumlah',
            'satuan', 'lokasi', 'tanggal_pembelian', 'harga_pembelian',
            'sumber_dana', 'keterangan',
        ]);

        $data['nama_barang'] = strtoupper($request->nama_barang);
        $data['status'] = 'aktif';

        // Upload foto utama
        if ($request->hasFile('foto_utama')) {
            $data['foto_utama'] = $request->file('foto_utama')->store('barang', 'public');
        }

        // Upload gallery
        if ($request->hasFile('foto_gallery')) {
            $gallery = [];
            foreach ($request->file('foto_gallery') as $file) {
                $gallery[] = $file->store('barang/gallery', 'public');
            }
            $data['foto_gallery'] = $gallery;
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:Elektronik,Perlengkapan,Furniture,ATK,Lainnya',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Perlu Perbaikan',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_pembelian' => 'nullable|date',
            'harga_pembelian' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'foto_utama' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_barang', 'kategori', 'kondisi', 'jumlah',
            'satuan', 'lokasi', 'tanggal_pembelian', 'harga_pembelian',
            'sumber_dana', 'keterangan',
        ]);

        $data['nama_barang'] = strtoupper($request->nama_barang);

        if ($request->hasFile('foto_utama')) {
            $data['foto_utama'] = $request->file('foto_utama')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Barang $barang)
    {
        $barang->update(['status' => 'dihapus']);
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function getKode()
    {
        $lastKode = Barang::latest('id')->first();
        $nextNumber = $lastKode ? intval(substr($lastKode->kode_barang, -3)) + 1 : 1;
        $kode = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        return response()->json(['kode' => $kode]);
    }
}
