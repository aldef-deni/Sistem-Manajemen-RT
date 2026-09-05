<?php

namespace App\Http\Controllers;

use App\Models\Arisan;
use App\Models\ArisanIuran;
use App\Models\AnggotaKeluarga;
use App\Models\RekeningKas;
use App\Models\User;
use Illuminate\Http\Request;

class ArisanController extends Controller
{
    public function index(Request $request)
    {
        $query = Arisan::withCount('peserta');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $arisan = $query->latest()->paginate(15)->withQueryString();

        $totalArisan = Arisan::count();
        $arisanAktif = Arisan::where('status', 'aktif')->count();
        $totalPeserta = \DB::table('arisan_peserta')->count();

        return view('arisan.index', compact('arisan', 'totalArisan', 'arisanAktif', 'totalPeserta'));
    }

    public function create()
    {
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();
        $rekenings = RekeningKas::where('is_active', true)->get();

        return view('arisan.create', compact('wargas', 'rekenings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal_iuran' => 'required|integer|min:1000|max:100000000',
            'periode' => 'required|in:mingguan,bulanan',
            'tanggal_mulai' => 'required|date',
            'mode_undian' => 'required|in:manual,otomatis',
            'pendamping_per_periode' => 'required|integer|min:1',
            'rekening_kas_id' => 'nullable|exists:rekening_kas,id',
            'keterangan' => 'nullable|string',
        ]);

        Arisan::create([
            'nama' => strtoupper($request->nama),
            'nominal_iuran' => $request->nominal_iuran,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'mode_undian' => $request->mode_undian,
            'jumlah_pemenang_per_pertemuan' => $request->pendamping_per_periode,
            'rekening_kas_id' => $request->rekening_kas_id,
            'keterangan' => $request->keterangan,
            'status' => 'aktif',
        ]);

        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil dibuat! Tambahkan peserta untuk memulai.');
    }

    public function show(Request $request, Arisan $arisan)
    {
        $arisan->load(['peserta.kartuKeluarga', 'rekening']);
        $wargas = AnggotaKeluarga::orderBy('nama_lengkap')->get();

        $periodeAktif = $this->periodeDiminta($request, $arisan);

        // Iuran periode yang sedang dilihat, dikunci ke id peserta supaya
        // tampilan tinggal memeriksa keberadaannya.
        $iuranPeriode = $arisan->iuran()
            ->where('periode_ke', $periodeAktif)
            ->get()
            ->keyBy('anggota_keluarga_id');

        $ringkasan = $this->ringkasanIuran($arisan);

        return view('arisan.show', compact(
            'arisan', 'wargas', 'periodeAktif', 'iuranPeriode', 'ringkasan'
        ));
    }

    public function edit(Arisan $arisan)
    {
        $arisan->load('peserta');
        $rekenings = RekeningKas::where('is_active', true)->get();

        return view('arisan.edit', compact('arisan', 'rekenings'));
    }

    public function update(Request $request, Arisan $arisan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nominal_iuran' => 'required|integer|min:1000|max:100000000',
            'periode' => 'required|in:mingguan,bulanan',
            'tanggal_mulai' => 'required|date',
            'mode_undian' => 'required|in:manual,otomatis',
            'jumlah_pemenang_per_pertemuan' => 'required|integer|min:1',
            'rekening_kas_id' => 'nullable|exists:rekening_kas,id',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $arisan->update([
            'nama' => strtoupper($request->nama),
            'nominal_iuran' => $request->nominal_iuran,
            'periode' => $request->periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'mode_undian' => $request->mode_undian,
            'jumlah_pemenang_per_pertemuan' => $request->jumlah_pemenang_per_pertemuan,
            'rekening_kas_id' => $request->rekening_kas_id,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil diupdate!');
    }

    public function destroy(Arisan $arisan)
    {
        $arisan->delete();
        return redirect()->route('arisan.index')->with('success', 'Arisan berhasil dihapus!');
    }

    public function tambahPeserta(Request $request, Arisan $arisan)
    {
        $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
        ]);

        // Check if already a participant
        $exists = $arisan->peserta()->where('anggota_keluarga_id', $request->anggota_keluarga_id)->exists();
        if ($exists) {
            return back()->with('error', 'Warga ini sudah menjadi peserta arisan!');
        }

        $urutan = $arisan->peserta()->count() + 1;
        $arisan->peserta()->attach($request->anggota_keluarga_id, [
            'urutan' => $urutan,
            'sudah_dapat' => false,
        ]);

        return back()->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function hapusPeserta(Arisan $arisan, $pesertaId)
    {
        // Catatan iuran ikut dibuang: pivot peserta dilepas dengan detach,
        // yang tidak menyentuh tabel arisan_iuran, sehingga barisnya akan
        // menggantung dan ikut terhitung pada total terkumpul.
        $arisan->iuran()->where('anggota_keluarga_id', $pesertaId)->delete();

        $arisan->peserta()->detach($pesertaId);

        return back()->with('success', 'Peserta dan catatan iurannya berhasil dihapus.');
    }

    public function undian(Request $request, Arisan $arisan)
    {
        // Get participants who haven't received yet
        $belumDapat = $arisan->peserta()->wherePivot('sudah_dapat', false)->get();

        if ($belumDapat->isEmpty()) {
            return back()->with('error', 'Semua peserta sudah mendapat arisan!');
        }

        // Random pick
        $pemenang = $belumDapat->random();
        $pemenang->pivot->update([
            'sudah_dapat' => true,
            'tanggal_dapat' => now(),
        ]);

        return back()->with('success', 'Pemenang arisan: ' . $pemenang->nama_lengkap . '!');
    }

    /*
    |--------------------------------------------------------------------------
    | Pencatatan iuran
    |--------------------------------------------------------------------------
    */

    /** Catat pembayaran satu peserta untuk satu periode. */
    public function bayarIuran(Request $request, Arisan $arisan)
    {
        $data = $request->validate([
            'anggota_keluarga_id' => 'required|exists:anggota_keluarga,id',
            'periode_ke'          => 'required|integer|min:1|max:600',
            'nominal'             => 'nullable|integer|min:0|max:100000000',
            'tanggal_bayar'       => 'nullable|date',
            'metode'              => 'nullable|in:tunai,transfer',
            'keterangan'          => 'nullable|string|max:255',
        ]);

        if ($arisan->status !== 'aktif') {
            return back()->with('error', 'Arisan sudah tidak aktif, iuran tidak dapat dicatat.');
        }

        if (! $this->pesertaArisan($arisan, $data['anggota_keluarga_id'])) {
            return back()->with('error', 'Warga tersebut bukan peserta arisan ini.');
        }

        $sudah = $arisan->iuran()
            ->where('anggota_keluarga_id', $data['anggota_keluarga_id'])
            ->where('periode_ke', $data['periode_ke'])
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Iuran peserta ini untuk periode tersebut sudah tercatat.');
        }

        $arisan->iuran()->create([
            'anggota_keluarga_id' => $data['anggota_keluarga_id'],
            'periode_ke'          => $data['periode_ke'],
            'nominal'             => $data['nominal'] ?? (int) $arisan->nominal_iuran,
            'tanggal_bayar'       => $data['tanggal_bayar'] ?? now()->toDateString(),
            'metode'              => $data['metode'] ?? 'tunai',
            'keterangan'          => $data['keterangan'] ?? null,
            'dicatat_oleh'        => auth()->id(),
        ]);

        return back()->with('success', 'Iuran berhasil dicatat.');
    }

    /** Catat sekaligus seluruh peserta yang belum membayar pada satu periode. */
    public function bayarIuranMassal(Request $request, Arisan $arisan)
    {
        $data = $request->validate([
            'periode_ke'    => 'required|integer|min:1|max:600',
            'tanggal_bayar' => 'nullable|date',
            'metode'        => 'nullable|in:tunai,transfer',
        ]);

        if ($arisan->status !== 'aktif') {
            return back()->with('error', 'Arisan sudah tidak aktif, iuran tidak dapat dicatat.');
        }

        $sudahBayar = $arisan->iuran()
            ->where('periode_ke', $data['periode_ke'])
            ->pluck('anggota_keluarga_id')
            ->all();

        $belum = $arisan->peserta()
            ->whereNotIn('anggota_keluarga.id', $sudahBayar)
            ->pluck('anggota_keluarga.id');

        if ($belum->isEmpty()) {
            return back()->with('error', 'Semua peserta sudah membayar untuk periode ini.');
        }

        $sekarang = now();

        $baris = $belum->map(fn ($id) => [
            'arisan_id'           => $arisan->id,
            'anggota_keluarga_id' => $id,
            'periode_ke'          => $data['periode_ke'],
            'nominal'             => (int) $arisan->nominal_iuran,
            'tanggal_bayar'       => $data['tanggal_bayar'] ?? $sekarang->toDateString(),
            'metode'              => $data['metode'] ?? 'tunai',
            'keterangan'          => 'Pencatatan massal',
            'dicatat_oleh'        => auth()->id(),
            'created_at'          => $sekarang,
            'updated_at'          => $sekarang,
        ])->all();

        ArisanIuran::insert($baris);

        return back()->with('success', count($baris) . ' peserta dicatat lunas untuk periode ini.');
    }

    /** Batalkan satu catatan iuran — untuk memperbaiki salah input. */
    public function hapusIuran(Arisan $arisan, ArisanIuran $iuran)
    {
        if ($iuran->arisan_id !== $arisan->id) {
            abort(404);
        }

        $iuran->delete();

        return back()->with('success', 'Catatan iuran dibatalkan.');
    }

    /** Halaman riwayat: matriks peserta terhadap seluruh periode. */
    public function riwayatIuran(Arisan $arisan)
    {
        $arisan->load('peserta');

        $jumlahPeriode = max(1, $arisan->jumlahPeriode());
        $periodeList   = range(1, $jumlahPeriode);

        // Dikelompokkan sebagai [anggota_keluarga_id][periode_ke] supaya
        // matriksnya tidak perlu mencari ulang di tiap sel.
        $matriks = $arisan->iuran()
            ->get()
            ->groupBy('anggota_keluarga_id')
            ->map(fn ($baris) => $baris->keyBy('periode_ke'));

        $ringkasan = $this->ringkasanIuran($arisan);

        return view('arisan.iuran', compact(
            'arisan', 'periodeList', 'matriks', 'ringkasan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Pembantu
    |--------------------------------------------------------------------------
    */

    private function pesertaArisan(Arisan $arisan, int|string $anggotaId): bool
    {
        return $arisan->peserta()->where('anggota_keluarga.id', $anggotaId)->exists();
    }

    /** Periode yang sedang dilihat: dari querystring, atau periode berjalan. */
    private function periodeDiminta(Request $request, Arisan $arisan): int
    {
        $diminta  = (int) $request->query('periode', 0);
        $maksimal = max(1, $arisan->jumlahPeriode());

        if ($diminta >= 1 && $diminta <= $maksimal) {
            return $diminta;
        }

        return $arisan->periodeSaatIni();
    }

    private function ringkasanIuran(Arisan $arisan): array
    {
        $terkumpul = $arisan->totalTerkumpul();
        $target    = $arisan->targetTerkumpul();

        return [
            'jumlah_peserta'   => $arisan->peserta()->count(),
            'jumlah_periode'   => $arisan->jumlahPeriode(),
            'terkumpul'        => $terkumpul,
            'target'           => $target,
            'persen'           => $target > 0 ? (int) round($terkumpul / $target * 100) : 0,
            'catatan_iuran'    => $arisan->iuran()->count(),
            'periode_berjalan' => $arisan->periodeSaatIni(),
        ];
    }
}
