<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBarang;
use App\Models\Barang;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;

class PeminjamanBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanBarang::with('barang');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_peminjaman', 'like', "%{$search}%")
                  ->orWhere('nama_peminjam', 'like', "%{$search}%");
            });
        }

        $peminjaman = $query->latest()->paginate(15)->withQueryString();

        // Stats
        $totalCatatan = PeminjamanBarang::count();
        $sedangDipinjam = PeminjamanBarang::where('status', 'dipinjam')->count();
        $dikembalikan = PeminjamanBarang::where('status', 'dikembalikan')->count();
        $terlambat = PeminjamanBarang::where('status', 'terlambat')->count();

        // Tab counts
        $statusCounts = [
            'semua' => $totalCatatan,
            'dipinjam' => $sedangDipinjam,
            'dikembalikan' => $dikembalikan,
            'terlambat' => $terlambat,
        ];

        return view('peminjaman.index', compact('peminjaman', 'totalCatatan', 'sedangDipinjam', 'dikembalikan', 'terlambat', 'statusCounts'));
    }

    public function create()
    {
        $lastKode = PeminjamanBarang::latest('id')->first();
        $nextNumber = $lastKode ? intval(substr($lastKode->kode_peminjaman, -4)) + 1 : 1;
        $kode = 'PJM-' . date('Ym') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $barangs = Barang::where('status', 'aktif')->where('jumlah', '>', 0)->orderBy('nama_barang')->get();
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();

        return view('peminjaman.create', compact('kode', 'barangs', 'wargas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_peminjaman' => 'required|unique:peminjaman_barang,kode_peminjaman',
            'barang_id' => 'required|exists:barang,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'kondisi_saat_pinjam' => 'required|string',
            'tanggal_pinjam' => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after:tanggal_pinjam',
            'keperluan' => 'nullable|string',
            'nama_peminjam' => 'required|string|max:255',
            'no_hp_peminjam' => 'nullable|string|max:20',
            'anggota_keluarga_id' => 'nullable|exists:anggota_keluarga,id',
        ]);

        // Check stock availability
        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->jumlah < $request->jumlah_pinjam) {
            return back()->withInput()->with('error', 'Stok barang tidak mencukupi! Tersisa: ' . $barang->jumlah . ' ' . $barang->satuan);
        }

        // Reduce stock
        $barang->decrement('jumlah', $request->jumlah_pinjam);

        // If stock becomes 0, mark as borrowed
        if ($barang->jumlah == 0) {
            $barang->update(['status' => 'dipinjam']);
        }

        PeminjamanBarang::create([
            'kode_peminjaman' => $request->kode_peminjaman,
            'barang_id' => $request->barang_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'kondisi_saat_pinjam' => $request->kondisi_saat_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_rencana_kembali' => $request->tanggal_rencana_kembali,
            'keperluan' => $request->keperluan,
            'nama_peminjam' => $request->nama_peminjam,
            'no_hp_peminjam' => $request->no_hp_peminjam,
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'status' => 'dipinjam',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function show(PeminjamanBarang $peminjaman)
    {
        $peminjaman->load('barang');
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan(Request $request, PeminjamanBarang $peminjaman)
    {
        $request->validate([
            'kondisi_saat_kembali' => 'required|string',
        ]);

        // Return stock
        $barang = $barang = $peminjaman->barang;
        $barang->increment('jumlah', $peminjaman->jumlah_pinjam);

        // Update barang status back to aktif
        if ($barang->status === 'dipinjam') {
            $barang->update(['status' => 'aktif']);
        }

        // Update kondisi barang if different
        if ($request->kondisi_saat_kembali !== 'Baik') {
            $barang->update(['kondisi' => $request->kondisi_saat_kembali]);
        }

        // Update peminjaman
        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'kondisi_saat_kembali' => $request->kondisi_saat_kembali,
        ]);

        return back()->with('success', 'Barang berhasil dikembalikan!');
    }

    public function destroy(PeminjamanBarang $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            // Return stock before deleting
            $peminjaman->barang->increment('jumlah', $peminjaman->jumlah_pinjam);
        }

        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Catatan peminjaman berhasil dihapus!');
    }
}
