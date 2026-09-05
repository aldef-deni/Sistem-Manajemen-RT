<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IuranWarga;
use App\Models\JadwalKegiatan;
use App\Models\KegiatanRT;
use App\Models\Pengaduan;
use App\Models\Pengumuman;
use App\Models\Polling;
use App\Models\RekeningKas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ringkasan untuk layar beranda aplikasi. Isinya menyesuaikan peran:
 * pengurus melihat angka kas dan tunggakan, warga melihat urusannya sendiri.
 */
class BerandaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user     = $request->user();
        $pengurus = in_array($user->role, ['admin', 'ketua', 'pengurus'], true);

        $pengumuman = Pengumuman::where('status', 'publish')
            ->latest('tanggal_publish')
            ->limit(3)
            ->get()
            ->map(fn ($p) => [
                'id'      => $p->id,
                'judul'   => $p->judul,
                'kategori' => $p->kategori,
                'tanggal' => optional($p->tanggal_publish)->toDateString(),
            ]);

        $jadwal = JadwalKegiatan::whereDate('tanggal_mulai', '>=', now()->toDateString())
            ->where('status', 'aktif')
            ->orderBy('tanggal_mulai')
            ->limit(3)
            ->get()
            ->map(fn ($j) => [
                'id'      => $j->id,
                'nama'    => $j->nama_kegiatan,
                'lokasi'  => $j->lokasi,
                'tanggal' => optional($j->tanggal_mulai)->toDateString(),
                'jam'     => $j->jam_mulai,
            ]);

        $ringkasan = [
            [
                'kunci' => 'pengumuman',
                'label' => 'Pengumuman Aktif',
                'nilai' => Pengumuman::where('status', 'publish')->count(),
                'jenis' => 'angka',
            ],
            [
                'kunci' => 'kegiatan',
                'label' => 'Kegiatan Mendatang',
                'nilai' => JadwalKegiatan::whereDate('tanggal_mulai', '>=', now()->toDateString())
                    ->where('status', 'aktif')->count(),
                'jenis' => 'angka',
            ],
            [
                'kunci' => 'polling',
                'label' => 'Polling Berjalan',
                'nilai' => Polling::where('status', 'aktif')->count(),
                'jenis' => 'angka',
            ],
        ];

        if ($pengurus) {
            $ringkasan[] = [
                'kunci' => 'saldo_kas',
                'label' => 'Saldo Kas RT',
                'nilai' => (float) RekeningKas::where('is_active', true)->sum('saldo'),
                'jenis' => 'rupiah',
            ];
            $ringkasan[] = [
                'kunci' => 'iuran_belum',
                'label' => 'Iuran Belum Lunas',
                'nilai' => IuranWarga::where('status', '!=', 'lunas')->count(),
                'jenis' => 'angka',
            ];
            $ringkasan[] = [
                'kunci' => 'pengaduan_baru',
                'label' => 'Pengaduan Baru',
                'nilai' => Pengaduan::where('status', 'baru')->count(),
                'jenis' => 'angka',
            ];
        } else {
            $wargaId = $user->anggota_keluarga_id;

            $ringkasan[] = [
                'kunci' => 'iuran_saya',
                'label' => 'Iuran Saya Belum Lunas',
                'nilai' => $wargaId
                    ? IuranWarga::where('anggota_keluarga_id', $wargaId)->where('status', '!=', 'lunas')->count()
                    : 0,
                'jenis' => 'angka',
            ];
            $ringkasan[] = [
                'kunci' => 'pengaduan_saya',
                'label' => 'Pengaduan Saya',
                'nilai' => Pengaduan::where('user_id', $user->id)->count(),
                'jenis' => 'angka',
            ];
        }

        return response()->json([
            'sapaan'     => $this->sapaan(),
            'ringkasan'  => $ringkasan,
            'pengumuman' => $pengumuman,
            'jadwal'     => $jadwal,
            'kegiatan'   => KegiatanRT::where('status', 'publish')
                ->latest('tanggal_mulai')
                ->limit(4)
                ->get()
                ->map(fn ($k) => [
                    'id'      => $k->id,
                    'judul'   => $k->judul,
                    'kategori' => $k->kategori,
                    'tanggal' => optional($k->tanggal_mulai)->toDateString(),
                    'foto_url' => $k->foto_utama ? url('storage/' . $k->foto_utama) : null,
                ]),
        ]);
    }

    private function sapaan(): string
    {
        $jam = (int) now()->format('H');

        return match (true) {
            $jam < 11 => 'Selamat pagi',
            $jam < 15 => 'Selamat siang',
            $jam < 19 => 'Selamat sore',
            default   => 'Selamat malam',
        };
    }
}
