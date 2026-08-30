<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RencanaPembelian;
use Illuminate\Http\Request;

class RencanaPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = RencanaPembelian::with('barang');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_rencana', 'like', "%{$search}%");
            });
        }

        $rencana = $query->latest()->paginate(15)->withQueryString();

        // Stats
        $total = RencanaPembelian::count();
        $pending = RencanaPembelian::where('status', 'direncanakan')->count();
        $terbeli = RencanaPembelian::where('status', 'terbeli')->count();
        $estPending = RencanaPembelian::where('status', '!=', 'terbeli')->where('status', '!=', 'dibatalkan')->sum('estimasi_harga');

        // Status counts for tabs
        $statusCounts = [
            'semua' => $total,
            'direncanakan' => RencanaPembelian::where('status', 'direncanakan')->count(),
            'disetujui' => RencanaPembelian::where('status', 'disetujui')->count(),
            'terbeli' => RencanaPembelian::where('status', 'terbeli')->count(),
            'hibah' => RencanaPembelian::where('status', 'hibah')->count(),
            'dibatalkan' => RencanaPembelian::where('status', 'dibatalkan')->count(),
        ];

        return view('barang.rencana', compact('rencana', 'total', 'pending', 'terbeli', 'estPending', 'statusCounts'));
    }

    public function create()
    {
        $lastKode = RencanaPembelian::latest('id')->first();
        $nextNumber = $lastKode ? intval(substr($lastKode->kode_rencana, -4)) + 1 : 1;
        $kode = 'RP' . date('Ym') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('barang.rencana-create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rencana' => 'required|unique:rencana_pembelian,kode_rencana',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:Elektronik,Perlengkapan,Furniture,ATK,Lainnya',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'prioritas' => 'required|in:tinggi,sedang,rendah',
            'estimasi_harga' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'tanggal_rencana' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only([
            'kode_rencana', 'nama_barang', 'kategori', 'jumlah', 'satuan',
            'prioritas', 'estimasi_harga', 'sumber_dana', 'tanggal_rencana', 'keterangan',
        ]);

        $data['nama_barang'] = strtoupper($request->nama_barang);
        $data['status'] = 'direncanakan';

        RencanaPembelian::create($data);

        return redirect()->route('barang.rencana.index')->with('success', 'Rencana pembelian berhasil dibuat!');
    }

    public function updateStatus(RencanaPembelian $rencana, Request $request)
    {
        $request->validate([
            'status' => 'required|in:direncanakan,disetujui,terbeli,hibah,dibatalkan',
        ]);

        $data = ['status' => $request->status];

        // If status is 'terbeli', create the barang
        if ($request->status === 'terbeli') {
            $lastKode = \App\Models\Barang::latest('id')->first();
            $nextNumber = $lastKode ? intval(substr($lastKode->kode_barang, -3)) + 1 : 1;
            $kode = 'INV-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $barang = Barang::create([
                'kode_barang' => $kode,
                'nama_barang' => $rencana->nama_barang,
                'kategori' => $rencana->kategori,
                'kondisi' => 'Baik',
                'jumlah' => $rencana->jumlah,
                'satuan' => $rencana->satuan,
                'lokasi' => 'Gudang',
                'tanggal_pembelian' => now(),
                'harga_pembelian' => $rencana->estimasi_harga,
                'sumber_dana' => $rencana->sumber_dana,
                'keterangan' => 'Dibeli dari rencana ' . $rencana->kode_rencana,
                'status' => 'aktif',
            ]);

            $data['barang_id'] = $barang->id;
        }

        $rencana->update($data);

        return back()->with('success', 'Status rencana berhasil diupdate!');
    }

    public function destroy(RencanaPembelian $rencana)
    {
        $rencana->delete();
        return redirect()->route('barang.rencana.index')->with('success', 'Rencana berhasil dihapus!');
    }
}
