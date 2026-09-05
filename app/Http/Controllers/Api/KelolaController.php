<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\IuranWarga;
use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use App\Models\RekeningKas;
use App\Models\TransaksiKas;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Bagian aplikasi yang hanya dibuka pengurus ke atas.
 *
 * Penjagaan aksesnya ada di routes/api.php lewat middleware role; kelas ini
 * mengandaikan pemanggilnya sudah lolos penjagaan itu.
 */
class KelolaController extends Controller
{
    /* ------------------------------------------------------------ ringkasan */

    public function ringkasan(): JsonResponse
    {
        $bulanIni = now();

        return response()->json([
            'kas' => [
                'saldo'      => (float) RekeningKas::where('is_active', true)->sum('saldo'),
                'masuk'      => (float) TransaksiKas::where('jenis', 'masuk')
                    ->whereMonth('tanggal', $bulanIni->month)->whereYear('tanggal', $bulanIni->year)->sum('nominal'),
                'keluar'     => (float) TransaksiKas::where('jenis', 'keluar')
                    ->whereMonth('tanggal', $bulanIni->month)->whereYear('tanggal', $bulanIni->year)->sum('nominal'),
                'bulan'      => $bulanIni->translatedFormat('F Y'),
            ],
            'warga' => [
                'jiwa'    => AnggotaKeluarga::count(),
                'kk'      => \App\Models\KartuKeluarga::count(),
            ],
            'iuran' => [
                'belum_lunas'   => IuranWarga::where('status', '!=', 'lunas')->count(),
                'total_tunggak' => (float) IuranWarga::where('status', '!=', 'lunas')->sum('nominal'),
            ],
            'pengaduan' => [
                'baru'     => Pengaduan::where('status', 'baru')->count(),
                'diproses' => Pengaduan::where('status', 'diproses')->count(),
            ],
        ]);
    }

    /* ---------------------------------------------------------------- warga */

    public function warga(Request $request): JsonResponse
    {
        $data = AnggotaKeluarga::with('kartuKeluarga')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $kata = $request->cari;
                $q->where(fn ($w) => $w->where('nama_lengkap', 'like', "%{$kata}%")->orWhere('nik', 'like', "%{$kata}%"));
            })
            ->orderBy('nama_lengkap')
            ->paginate(20);

        return response()->json([
            'data' => collect($data->items())->map(fn ($a) => [
                'id'      => $a->id,
                'nama'    => $a->nama_lengkap,
                'nik'     => $a->nik,
                'no_hp'   => $a->no_hp,
                'kelamin' => $a->jenis_kelamin === 'L' ? 'Laki-laki' : ($a->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                'hubungan' => $a->status_hubungan,
                'domisili' => $a->domisili,
                'no_kk'   => optional($a->kartuKeluarga)->no_kk,
                'alamat'  => optional($a->kartuKeluarga)->alamat,
            ])->values(),
            'halaman' => [
                'saat_ini' => $data->currentPage(),
                'terakhir' => $data->lastPage(),
                'total'    => $data->total(),
            ],
        ]);
    }

    /* ------------------------------------------------------------------ kas */

    public function kas(): JsonResponse
    {
        $rekening = RekeningKas::where('is_active', true)->get()->map(fn ($r) => [
            'id'    => $r->id,
            'nama'  => $r->nama,
            'jenis' => $r->jenis,
            'saldo' => (float) $r->saldo,
        ]);

        $transaksi = TransaksiKas::with('rekening')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(30)
            ->get()
            ->map(fn ($t) => [
                'id'         => $t->id,
                'jenis'      => $t->jenis,
                'masuk'      => $t->jenis === 'masuk',
                'kategori'   => $t->kategori,
                'nominal'    => (float) $t->nominal,
                'tanggal'    => optional($t->tanggal)->toDateString(),
                'keterangan' => $t->keterangan,
                'rekening'   => optional($t->rekening)->nama,
            ]);

        return response()->json([
            'saldo_total' => (float) $rekening->sum('saldo'),
            'rekening'    => $rekening,
            'transaksi'   => $transaksi,
        ]);
    }

    /* ---------------------------------------------------------------- iuran */

    public function iuran(Request $request): JsonResponse
    {
        $data = IuranWarga::with(['anggota', 'jenisIuran'])
            ->when($request->input('status') === 'belum', fn ($q) => $q->where('status', '!=', 'lunas'))
            ->when($request->input('status') === 'lunas', fn ($q) => $q->where('status', 'lunas'))
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(20);

        return response()->json([
            'ringkasan' => [
                'belum_lunas'   => IuranWarga::where('status', '!=', 'lunas')->count(),
                'total_tunggak' => (float) IuranWarga::where('status', '!=', 'lunas')->sum('nominal'),
            ],
            'data' => collect($data->items())->map(fn ($i) => [
                'id'      => $i->id,
                'warga'   => optional($i->anggota)->nama_lengkap ?? '-',
                'jenis'   => optional($i->jenisIuran)->nama ?? 'Iuran',
                'periode' => $this->namaBulan($i->bulan) . ' ' . $i->tahun,
                'nominal' => (float) $i->nominal,
                'status'  => $i->status,
                'lunas'   => $i->status === 'lunas',
            ])->values(),
            'halaman' => [
                'saat_ini' => $data->currentPage(),
                'terakhir' => $data->lastPage(),
                'total'    => $data->total(),
            ],
        ]);
    }

    /** Menandai satu tagihan lunas — pekerjaan bendahara di lapangan. */
    public function tandaiLunas(IuranWarga $iuran): JsonResponse
    {
        if ($iuran->status === 'lunas') {
            return response()->json(['pesan' => 'Tagihan ini sudah lunas.'], 422);
        }

        $iuran->update(['status' => 'lunas', 'tanggal_bayar' => now()->toDateString()]);

        return response()->json(['pesan' => 'Tagihan ditandai lunas.']);
    }

    /* ------------------------------------------------------------ pengaduan */

    public function ubahStatusPengaduan(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:baru,diterima,diproses,selesai,ditolak',
        ]);

        $pengaduan->update($data);

        return response()->json(['pesan' => 'Status pengaduan diperbarui.']);
    }

    public function balasPengaduan(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        $data = $request->validate([
            'pesan' => 'required|string|max:2000',
        ]);

        PengaduanBalasan::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id'      => $request->user()->id,
            'pesan'        => $data['pesan'],
        ]);

        $pengaduan->update([
            'balasan'       => $data['pesan'],
            'dibalas_oleh'  => $request->user()->name,
            'tanggal_balas' => now(),
        ]);

        return response()->json(['pesan' => 'Balasan terkirim.'], 201);
    }

    /* ----------------------------------------------------- akun (admin/ketua) */

    public function akun(Request $request): JsonResponse
    {
        $data = User::when($request->filled('cari'), function ($q) use ($request) {
            $kata = $request->cari;
            $q->where(fn ($w) => $w->where('name', 'like', "%{$kata}%")->orWhere('username', 'like', "%{$kata}%"));
        })
            // CASE, bukan FIELD(): FIELD hanya ada di MySQL sedangkan uji
            // otomatis berjalan di SQLite.
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'ketua' THEN 2 WHEN 'pengurus' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->paginate(25);

        return response()->json([
            'data' => collect($data->items())->map(fn ($u) => [
                'id'       => $u->id,
                'nama'     => $u->name,
                'username' => $u->username,
                'email'    => $u->email,
                'peran'    => $u->role,
                'peran_label' => match ($u->role) {
                    'admin'    => 'Administrator',
                    'ketua'    => 'Ketua RT',
                    'pengurus' => 'Pengurus RT',
                    default    => 'Warga',
                },
                'tertaut' => (bool) $u->anggota_keluarga_id,
            ])->values(),
            'halaman' => [
                'saat_ini' => $data->currentPage(),
                'terakhir' => $data->lastPage(),
                'total'    => $data->total(),
            ],
        ]);
    }

    public function ubahPeran(Request $request, User $akun): JsonResponse
    {
        $data = $request->validate([
            'peran' => 'required|in:ketua,pengurus,warga',
        ]);

        // Penjagaan yang sama dengan sisi web: akun Administrator tidak boleh
        // diturunkan perannya oleh siapa pun selain dirinya sendiri.
        if ($akun->role === 'admin') {
            return response()->json(['pesan' => 'Peran Administrator tidak dapat diubah dari aplikasi.'], 422);
        }

        if ($akun->id === $request->user()->id) {
            return response()->json(['pesan' => 'Anda tidak dapat mengubah peran akun sendiri.'], 422);
        }

        $akun->update(['role' => $data['peran']]);

        return response()->json(['pesan' => 'Peran diperbarui.']);
    }

    public function resetPassword(Request $request, User $akun): JsonResponse
    {
        if ($akun->role === 'admin' && $akun->id !== $request->user()->id) {
            return response()->json(['pesan' => 'Password Administrator tidak dapat diatur ulang dari aplikasi.'], 422);
        }

        $baru = Str::password(10, symbols: false);
        $akun->update(['password' => Hash::make($baru)]);
        $akun->tokens()->delete();

        return response()->json([
            'pesan'    => 'Password diatur ulang. Sampaikan ke pemilik akun dan minta segera menggantinya.',
            'password' => $baru,
        ]);
    }

    private function namaBulan(?int $bulan): string
    {
        $nama = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return $nama[$bulan] ?? '-';
    }
}
