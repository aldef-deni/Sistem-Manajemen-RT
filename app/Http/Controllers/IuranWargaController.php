<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\IuranWarga;
use App\Models\JenisIuran;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;

class IuranWargaController extends Controller
{
    public function index(Request $request)
    {
        $query = IuranWarga::with(['anggota.kartuKeluarga', 'jenisIuran']);

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $iuran = $query->latest('tahun')->latest('bulan')->paginate(15)->withQueryString();

        // Stats
        $totalTagihan = IuranWarga::count();
        $belumBayar = IuranWarga::where('status', 'belum_bayar')->count();
        $sudahBayar = IuranWarga::where('status', 'lunas')->count();
        $totalBelumBayarNominal = IuranWarga::where('status', 'belum_bayar')->sum('nominal');
        $totalLunasNominal = IuranWarga::where('status', 'lunas')->sum('nominal');

        // Unique years for filter
        $years = IuranWarga::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');
        $jenisIurans = JenisIuran::where('is_active', true)->get();

        return view('iuran-warga.index', compact(
            'iuran', 'totalTagihan', 'belumBayar', 'sudahBayar',
            'totalBelumBayarNominal', 'totalLunasNominal', 'years', 'jenisIurans'
        ));
    }

    public function create()
    {
        $warga = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        $jenisIurans = JenisIuran::where('is_active', true)->get();

        return view('iuran-warga.create', compact('warga', 'jenisIurans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'jenis_iuran_id' => 'required|exists:jenis_iuran,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2030',
            'nominal' => 'required|integer|min:0|max:100000000',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Check duplicate
        $exists = IuranWarga::where([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'jenis_iuran_id' => $request->jenis_iuran_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ])->exists();

        if ($exists) {
            return back()->withErrors(['anggota_keluarga_id' => 'Tagihan iuran untuk warga ini pada periode tersebut sudah ada.'])->withInput();
        }

        $iuran = IuranWarga::create([
            'anggota_keluarga_id' => $request->anggota_keluarga_id,
            'jenis_iuran_id' => $request->jenis_iuran_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nominal' => $request->nominal,
            'status' => 'belum_bayar',
            'catatan' => $request->catatan,
        ]);
        $this->notifyResident(
            $iuran,
            'Tagihan iuran baru',
            'Tagihan '.$iuran->jenisIuran?->nama.' periode '.$iuran->periode.' sebesar '.$iuran->nominal_formatted.' telah dibuat.',
            'amber'
        );

        return redirect()->route('iuran-warga.index')->with('success', 'Tagihan iuran berhasil dibuat!');
    }

    public function edit(IuranWarga $iuran_warga)
    {
        $iuran_warga->load(['anggota.kartuKeluarga', 'jenisIuran']);
        $warga = AnggotaKeluarga::with('kartuKeluarga')->orderBy('nama_lengkap')->get();
        $jenisIurans = JenisIuran::where('is_active', true)->get();

        return view('iuran-warga.edit', compact('iuran_warga', 'warga', 'jenisIurans'));
    }

    public function update(Request $request, IuranWarga $iuran_warga)
    {
        $request->validate([
            'nominal' => 'required|integer|min:0|max:100000000',
            'catatan' => 'nullable|string|max:500',
        ]);

        $iuran_warga->update([
            'nominal' => $request->nominal,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('iuran-warga.index')->with('success', 'Tagihan iuran berhasil diupdate!');
    }

    public function destroy(IuranWarga $iuran_warga)
    {
        $iuran_warga->delete();

        return redirect()->route('iuran-warga.index')->with('success', 'Tagihan iuran berhasil dihapus!');
    }

    public function bayar(IuranWarga $iuran_warga)
    {
        $iuran_warga->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
        ]);
        $this->notifyResident(
            $iuran_warga,
            'Pembayaran iuran dikonfirmasi',
            'Pembayaran '.$iuran_warga->jenisIuran?->nama.' periode '.$iuran_warga->periode.' telah dinyatakan lunas.',
            'emerald'
        );

        return redirect()->route('iuran-warga.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function bayarMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:iuran_warga,id',
        ]);

        $iuranItems = IuranWarga::query()->whereIn('id', $request->ids)->get();

        IuranWarga::whereIn('id', $request->ids)->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        foreach ($iuranItems as $iuran) {
            $this->notifyResident(
                $iuran,
                'Pembayaran iuran dikonfirmasi',
                'Pembayaran '.$iuran->jenisIuran?->nama.' periode '.$iuran->periode.' telah dinyatakan lunas.',
                'emerald'
            );
        }

        return redirect()->route('iuran-warga.index')->with('success', count($request->ids).' pembayaran berhasil dikonfirmasi!');
    }

    private function notifyResident(IuranWarga $iuran, string $title, string $message, string $tone): void
    {
        $recipient = User::query()
            ->where('role', 'warga')
            ->where('anggota_keluarga_id', $iuran->anggota_keluarga_id)
            ->first();

        $recipient?->notify(new SystemNotification(
            category: 'payment',
            title: $title,
            message: $message,
            routeName: 'pembayaran',
            tone: $tone,
            context: ['iuran_id' => $iuran->id],
        ));
    }
}
