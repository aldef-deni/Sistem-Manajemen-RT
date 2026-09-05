<?php

namespace App\Http\Controllers;

use App\Models\RekeningKas;
use App\Models\TransaksiKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasRTController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiKas::with(['rekening', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kategori', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('rekening', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $transaksi = $query->latest('tanggal')->latest('id')->paginate(15)->withQueryString();

        $now = now();
        $pemasukanBulan = TransaksiKas::where('jenis', 'masuk')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('nominal');

        $pengeluaranBulan = TransaksiKas::where('jenis', 'keluar')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->sum('nominal');

        $saldoTotal = RekeningKas::where('is_active', true)->sum('saldo');

        $rekenings = RekeningKas::where('is_active', true)->get();

        // Diambil di PHP, bukan lewat fungsi tanggal bawaan database, supaya
        // kueri yang sama jalan di SQLite (lokal) dan MySQL (produksi).
        $years = TransaksiKas::query()
            ->whereNotNull('tanggal')
            ->pluck('tanggal')
            ->map(fn ($tanggal) => (int) $tanggal->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('kas-rt.index', compact(
            'transaksi', 'pemasukanBulan', 'pengeluaranBulan', 'saldoTotal',
            'rekenings', 'years'
        ));
    }

    public function pemasukan()
    {
        $rekenings = RekeningKas::where('is_active', true)->get();
        $categories = [
            'Setoran Tabungan',
            'Iuran Kebersihan',
            'Iuran Keamanan',
            'Iuran Sosial',
            'Setoran Kas RT',
            'Pembangunan',
            'Pemasukan Lainnya',
        ];

        return view('kas-rt.pemasukan', compact('rekenings', 'categories'));
    }

    public function storePemasukan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'rekening_kas_id' => 'required|exists:rekening_kas,id',
            'nominal' => 'required|integer|min:1|max:100000000',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            TransaksiKas::create([
                'tanggal' => $request->tanggal,
                'jenis' => 'masuk',
                'kategori' => $request->kategori,
                'rekening_kas_id' => $request->rekening_kas_id,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
            ]);

            $rekening = RekeningKas::findOrFail($request->rekening_kas_id);
            $rekening->increment('saldo', $request->nominal);

            DB::commit();
            return redirect()->route('kas-rt.index')->with('success', 'Pemasukan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat pemasukan: ' . $e->getMessage());
        }
    }

    public function pengeluaran()
    {
        $rekenings = RekeningKas::where('is_active', true)->get();
        $categories = [
            'Beli Alat Kebersihan',
            'Biaya Keamanan',
            'Biaya Sosial',
            'Pembangunan RT',
            'Operasional RT',
            'Kas RT Warga',
            'Pengeluaran Lainnya',
        ];

        return view('kas-rt.pengeluaran', compact('rekenings', 'categories'));
    }

    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'rekening_kas_id' => 'required|exists:rekening_kas,id',
            'nominal' => 'required|integer|min:1|max:100000000',
            'keterangan' => 'nullable|string',
            'bukti_dokumen' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_dokumen')) {
            $buktiPath = $request->file('bukti_dokumen')->store('bukti', 'public');
        }

        DB::beginTransaction();
        try {
            $rekening = RekeningKas::findOrFail($request->rekening_kas_id);

            if ($rekening->saldo < $request->nominal) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Saldo rekening tidak mencukupi! Saldo saat ini: Rp ' . number_format($rekening->saldo, 0, ',', '.'));
            }

            TransaksiKas::create([
                'tanggal' => $request->tanggal,
                'jenis' => 'keluar',
                'kategori' => $request->kategori,
                'rekening_kas_id' => $request->rekening_kas_id,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'bukti_dokumen' => $buktiPath,
                'user_id' => Auth::id(),
            ]);

            $rekening->decrement('saldo', $request->nominal);

            DB::commit();
            return redirect()->route('kas-rt.index')->with('success', 'Pengeluaran berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat pengeluaran: ' . $e->getMessage());
        }
    }

    public function destroy(TransaksiKas $transaksi)
    {
        DB::beginTransaction();
        try {
            $rekening = $rekening = $transaksi->rekening;

            if ($transaksi->jenis === 'masuk') {
                $rekening->decrement('saldo', $transaksi->nominal);
            } else {
                $rekening->increment('saldo', $transaksi->nominal);
            }

            $transaksi->delete();

            DB::commit();
            return redirect()->route('kas-rt.index')->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
