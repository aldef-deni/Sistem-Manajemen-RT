<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IuranWarga;
use App\Models\Pengaduan;
use App\Models\Polling;
use App\Models\PollingVote;
use App\Models\Tabungan;
use App\Support\SafeUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Layanan mandiri warga: iuran sendiri, pengaduan, dan polling.
 */
class LayananController extends Controller
{
    /* ---------------------------------------------------------------- iuran */

    public function iuranSaya(Request $request): JsonResponse
    {
        $wargaId = $request->user()->anggota_keluarga_id;

        if (! $wargaId) {
            return response()->json([
                'tertaut' => false,
                'pesan'   => 'Akun Anda belum ditautkan ke data kependudukan. Hubungi pengurus RT.',
                'data'    => [],
                'ringkasan' => ['belum_lunas' => 0, 'total_tagihan' => 0],
            ]);
        }

        $iuran = IuranWarga::with('jenisIuran')
            ->where('anggota_keluarga_id', $wargaId)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->limit(48)
            ->get();

        $belum = $iuran->where('status', '!=', 'lunas');

        return response()->json([
            'tertaut'   => true,
            'ringkasan' => [
                'belum_lunas'   => $belum->count(),
                'total_tagihan' => (float) $belum->sum('nominal'),
                'tabungan'      => (float) (Tabungan::where('anggota_keluarga_id', $wargaId)->value('saldo') ?? 0),
            ],
            'data' => $iuran->map(fn ($i) => [
                'id'      => $i->id,
                'jenis'   => optional($i->jenisIuran)->nama ?? 'Iuran',
                'bulan'   => $i->bulan,
                'tahun'   => $i->tahun,
                'periode' => $this->namaBulan($i->bulan) . ' ' . $i->tahun,
                'nominal' => (float) $i->nominal,
                'status'  => $i->status,
                'lunas'   => $i->status === 'lunas',
            ])->values(),
        ]);
    }

    /* ------------------------------------------------------------ pengaduan */

    public function pengaduan(Request $request): JsonResponse
    {
        $user = $request->user();
        $pengurus = in_array($user->role, ['admin', 'ketua', 'pengurus'], true);

        $data = Pengaduan::with('user')
            // Warga hanya melihat pengaduannya sendiri dan yang dibuka publik.
            ->when(! $pengurus, fn ($q) => $q->where(fn ($w) => $w->where('user_id', $user->id)->orWhere('privasi', 'publik')))
            ->latest('id')
            ->paginate(15);

        return response()->json([
            'data' => collect($data->items())->map(fn ($p) => [
                'id'         => $p->id,
                'kode_tiket' => $p->kode_tiket,
                'judul'      => $p->judul,
                'kategori'   => $p->kategori,
                'status'     => $p->status,
                'privasi'    => $p->privasi,
                'milik_saya' => $p->user_id === $user->id,
                'pelapor'    => $p->privasi === 'privat' && $p->user_id !== $user->id && ! $pengurus
                    ? 'Anonim'
                    : optional($p->user)->name,
                'tanggal'    => optional($p->created_at)->toDateTimeString(),
                'cuplikan'   => Str::limit($p->isi_pengaduan, 120),
            ])->values(),
            'halaman' => [
                'saat_ini' => $data->currentPage(),
                'terakhir' => $data->lastPage(),
                'total'    => $data->total(),
            ],
        ]);
    }

    public function pengaduanDetail(Request $request, Pengaduan $pengaduan): JsonResponse
    {
        $user = $request->user();
        $pengurus = in_array($user->role, ['admin', 'ketua', 'pengurus'], true);

        abort_unless($pengurus || $pengaduan->user_id === $user->id || $pengaduan->privasi === 'publik', 403);

        $pengaduan->load(['user', 'replies.user']);

        return response()->json([
            'id'           => $pengaduan->id,
            'kode_tiket'   => $pengaduan->kode_tiket,
            'judul'        => $pengaduan->judul,
            'kategori'     => $pengaduan->kategori,
            'status'       => $pengaduan->status,
            'isi'          => $pengaduan->isi_pengaduan,
            'tanggal'      => optional($pengaduan->created_at)->toDateTimeString(),
            'lampiran_url' => $pengaduan->lampiran ? url($pengaduan->lampiran) : null,
            'balasan'      => $pengaduan->replies->map(fn ($b) => [
                'id'      => $b->id,
                'oleh'    => optional($b->user)->name ?? 'Pengurus',
                'isi'     => $b->balasan ?? $b->pesan ?? '',
                'tanggal' => optional($b->created_at)->toDateTimeString(),
            ])->values(),
        ]);
    }

    public function kirimPengaduan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'judul'         => 'required|string|max:200',
            'kategori'      => 'required|string|max:50',
            'isi_pengaduan' => 'required|string',
            'privasi'       => 'required|in:publik,privat',
            'lampiran'      => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $data['user_id']    = $request->user()->id;
        $data['kode_tiket'] = 'ADU-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = SafeUpload::store(
                $request->file('lampiran'),
                'pengaduan',
                'lampiran',
                SafeUpload::DOCUMENT
            );
        }

        $pengaduan = Pengaduan::create($data);

        return response()->json([
            'pesan'      => 'Pengaduan terkirim.',
            'id'         => $pengaduan->id,
            'kode_tiket' => $pengaduan->kode_tiket,
        ], 201);
    }

    /* -------------------------------------------------------------- polling */

    public function polling(Request $request): JsonResponse
    {
        $user = $request->user();

        $daftar = Polling::latest('id')->limit(30)->get();

        $suaraSaya = PollingVote::where('user_id', $user->id)
            ->whereIn('polling_id', $daftar->pluck('id'))
            ->pluck('pilihan', 'polling_id');

        return response()->json([
            'data' => $daftar->map(function ($p) use ($suaraSaya) {
                $opsi = is_array($p->opsi) ? $p->opsi : (json_decode($p->opsi, true) ?: []);
                $rekap = PollingVote::where('polling_id', $p->id)
                    ->selectRaw('pilihan, COUNT(*) as jumlah')
                    ->groupBy('pilihan')
                    ->pluck('jumlah', 'pilihan');

                $total = max(1, (int) $rekap->sum());

                return [
                    'id'            => $p->id,
                    'judul'         => $p->judul,
                    'deskripsi'     => $p->deskripsi,
                    'status'        => $p->status,
                    'aktif'         => $p->status === 'aktif',
                    'boleh_ganti'   => (bool) $p->izinkan_ganti,
                    'tampilkan_hasil' => (bool) $p->tampilkan_hasil,
                    'jumlah_suara'  => (int) $p->jumlah_suara,
                    'pilihan_saya'  => $suaraSaya[$p->id] ?? null,
                    'berakhir'      => optional($p->tanggal_selesai)->toDateString(),
                    'opsi'          => collect($opsi)->map(fn ($o) => [
                        'teks'   => $o,
                        'jumlah' => (int) ($rekap[$o] ?? 0),
                        'persen' => (int) round(((int) ($rekap[$o] ?? 0)) / $total * 100),
                    ])->values(),
                ];
            })->values(),
        ]);
    }

    public function pilihPolling(Request $request, Polling $polling): JsonResponse
    {
        $opsi = is_array($polling->opsi) ? $polling->opsi : (json_decode($polling->opsi, true) ?: []);

        $data = $request->validate([
            'pilihan' => 'required|string|in:' . implode(',', $opsi),
        ]);

        if ($polling->status !== 'aktif') {
            return response()->json(['pesan' => 'Polling sudah tidak aktif.'], 422);
        }

        $suara = PollingVote::where('polling_id', $polling->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($suara) {
            if (! $polling->izinkan_ganti) {
                return response()->json(['pesan' => 'Anda sudah memberikan suara dan tidak boleh menggantinya.'], 422);
            }
            $suara->update(['pilihan' => $data['pilihan']]);
        } else {
            PollingVote::create([
                'polling_id' => $polling->id,
                'user_id'    => $request->user()->id,
                'pilihan'    => $data['pilihan'],
            ]);
            $polling->increment('jumlah_suara');
        }

        return response()->json(['pesan' => 'Suara tersimpan.']);
    }

    private function namaBulan(?int $bulan): string
    {
        $nama = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return $nama[$bulan] ?? '-';
    }
}
