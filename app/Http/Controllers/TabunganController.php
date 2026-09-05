<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\RekeningKas;
use App\Models\Tabungan;
use App\Models\TabunganTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TabunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Tabungan::with(['anggota']);

        if ($request->filled('jenis')) {
            $query->where('jenis_tabungan', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $tabungan = $query->latest()->paginate(10)->withQueryString();

        $totalSaldo = Tabungan::sum('saldo');
        $totalRekening = Tabungan::count();
        $rekeningAktif = Tabungan::where('status', 'aktif')->count();
        $totalWarga = Tabungan::distinct('anggota_keluarga_id')->count('anggota_keluarga_id');

        return view('tabungan.index', compact(
            'tabungan', 'totalSaldo', 'totalRekening', 'rekeningAktif', 'totalWarga'
        ));
    }

    public function show(Tabungan $tabungan)
    {
        $tabungan->load(['anggota', 'transaksi.rekening', 'transaksi.user']);
        return view('tabungan.show', compact('tabungan'));
    }

    public function setoran()
    {
        $wargas = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        $rekenings = RekeningKas::where('is_active', true)->get();

        return view('tabungan.setoran', compact('wargas', 'rekenings'));
    }

    public function storeSetoran(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'rekening_kas_id' => 'required|exists:rekening_kas,id',
            'jenis_tabungan' => 'required|in:sukarela,wajib,investasi',
            'nominal' => 'required|integer|min:10000|max:100000000',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Find or create tabungan account
            $tabungan = Tabungan::where('anggota_keluarga_id', $request->anggota_keluarga_id)
                ->where('jenis_tabungan', $request->jenis_tabungan)
                ->first();

            if (!$tabungan) {
                $tabungan = Tabungan::create([
                    'anggota_keluarga_id' => $request->anggota_keluarga_id,
                    'no_rekening' => 'TBG-' . strtoupper(Str::random(8)),
                    'jenis_tabungan' => $request->jenis_tabungan,
                    'saldo' => 0,
                    'status' => 'aktif',
                ]);
            }

            $saldoSebelum = $tabungan->saldo;
            $tabungan->increment('saldo', $request->nominal);
            $saldoSesudah = $tabungan->fresh()->saldo;

            TabunganTransaksi::create([
                'tabungan_id' => $tabungan->id,
                'jenis' => 'setoran',
                'nominal' => $request->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'rekening_kas_id' => $request->rekening_kas_id,
                'keterangan' => $request->keterangan,
                'status' => 'dikonfirmasi',
                'user_id' => Auth::id(),
            ]);

            // Update kas RT
            $rekening = RekeningKas::findOrFail($request->rekening_kas_id);
            $rekening->increment('saldo', $request->nominal);

            DB::commit();
            return redirect()->route('tabungan.index')->with('success', 'Setoran tabungan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat setoran: ' . $e->getMessage());
        }
    }

    public function penarikan()
    {
        $wargas = AnggotaKeluarga::with('tabungan')->orderBy('nama_lengkap')->get();
        return view('tabungan.penarikan', compact('wargas'));
    }

    public function storePenarikan(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'nominal' => 'required|integer|min:10000|max:100000000',
            'keterangan' => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            // lockForUpdate menahan baris sampai transaksi selesai. Tanpa itu
            // dua penarikan yang tiba bersamaan sama-sama lolos pemeriksaan
            // saldo dan rekening bisa menjadi minus.
            $tabungan = Tabungan::where('anggota_keluarga_id', $request->anggota_keluarga_id)
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->firstOrFail();

            if ($tabungan->saldo < $request->nominal) {
                DB::rollBack();
                return back()->withInput()->with('error', 'Saldo tabungan tidak mencukupi! Saldo saat ini: Rp ' . number_format($tabungan->saldo, 0, ',', '.'));
            }

            $saldoSebelum = $tabungan->saldo;
            $tabungan->decrement('saldo', $request->nominal);
            $saldoSesudah = $tabungan->fresh()->saldo;

            TabunganTransaksi::create([
                'tabungan_id' => $tabungan->id,
                'jenis' => 'penarikan',
                'nominal' => $request->nominal,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'keterangan' => $request->keterangan,
                'status' => 'dikonfirmasi',
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('tabungan.index')->with('success', 'Penarikan tabungan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat penarikan: ' . $e->getMessage());
        }
    }

    public function getSaldo(Request $request)
    {
        $tabungan = Tabungan::where('anggota_keluarga_id', $request->anggota_id)
            ->where('status', 'aktif')
            ->first();

        return response()->json([
            'saldo' => $tabungan ? $tabungan->saldo : 0,
            'no_rekening' => $tabungan ? $tabungan->no_rekening : '-',
        ]);
    }
}
