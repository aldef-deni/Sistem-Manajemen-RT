<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKegiatan;
use App\Models\KegiatanRT;
use App\Models\PengurusRT;
use App\Models\Pengumuman;
use App\Models\UMKM;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Informasi yang boleh dibaca semua warga: pengumuman, kegiatan, jadwal,
 * struktur pengurus, dan direktori UMKM.
 */
class InformasiController extends Controller
{
    public function pengumuman(Request $request): JsonResponse
    {
        $data = Pengumuman::where('status', 'publish')
            ->when($request->filled('cari'), fn ($q) => $q->where('judul', 'like', '%' . $request->cari . '%'))
            ->latest('tanggal_publish')
            ->paginate(15);

        return $this->halaman($data, fn ($p) => [
            'id'       => $p->id,
            'judul'    => $p->judul,
            'kategori' => $p->kategori,
            'target'   => $p->target,
            'tanggal'  => optional($p->tanggal_publish)->toDateString(),
            'cuplikan' => \Illuminate\Support\Str::limit(strip_tags($p->isi), 140),
        ]);
    }

    public function pengumumanDetail(Pengumuman $pengumuman): JsonResponse
    {
        abort_unless($pengumuman->status === 'publish', 404);

        return response()->json([
            'id'           => $pengumuman->id,
            'judul'        => $pengumuman->judul,
            'kategori'     => $pengumuman->kategori,
            'target'       => $pengumuman->target,
            'tanggal'      => optional($pengumuman->tanggal_publish)->toDateString(),
            'berakhir'     => optional($pengumuman->tanggal_berakhir)->toDateString(),
            'isi_html'     => $pengumuman->isi,
            'isi_teks'     => trim(strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $pengumuman->isi))),
            'lampiran_url' => $pengumuman->lampiran ? url($pengumuman->lampiran) : null,
        ]);
    }

    public function kegiatan(Request $request): JsonResponse
    {
        $data = KegiatanRT::where('status', 'publish')
            ->latest('tanggal_mulai')
            ->paginate(15);

        return $this->halaman($data, fn ($k) => [
            'id'       => $k->id,
            'judul'    => $k->judul,
            'kategori' => $k->kategori,
            'lokasi'   => $k->lokasi,
            'tanggal'  => optional($k->tanggal_mulai)->toDateString(),
            'foto_url' => $k->foto_utama ? url('storage/' . $k->foto_utama) : null,
            'cuplikan' => \Illuminate\Support\Str::limit(strip_tags($k->artikel), 140),
        ]);
    }

    public function kegiatanDetail(KegiatanRT $kegiatan): JsonResponse
    {
        abort_unless($kegiatan->status === 'publish', 404);

        $galeri = $kegiatan->galeri_foto;
        if (is_string($galeri)) {
            $galeri = json_decode($galeri, true) ?: [];
        }

        return response()->json([
            'id'          => $kegiatan->id,
            'judul'       => $kegiatan->judul,
            'kategori'    => $kegiatan->kategori,
            'lokasi'      => $kegiatan->lokasi,
            'tanggal'     => optional($kegiatan->tanggal_mulai)->toDateString(),
            'selesai'     => optional($kegiatan->tanggal_selesai)->toDateString(),
            'artikel'     => trim(strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $kegiatan->artikel))),
            'foto_url'    => $kegiatan->foto_utama ? url('storage/' . $kegiatan->foto_utama) : null,
            'galeri_url'  => collect($galeri ?: [])->map(fn ($f) => url('storage/' . $f))->values(),
        ]);
    }

    public function jadwal(Request $request): JsonResponse
    {
        $data = JadwalKegiatan::where('status', 'aktif')
            ->when(
                $request->boolean('lampau'),
                fn ($q) => $q->whereDate('tanggal_mulai', '<', now()->toDateString())->orderByDesc('tanggal_mulai'),
                fn ($q) => $q->whereDate('tanggal_mulai', '>=', now()->toDateString())->orderBy('tanggal_mulai'),
            )
            ->paginate(20);

        return $this->halaman($data, fn ($j) => [
            'id'        => $j->id,
            'nama'      => $j->nama_kegiatan,
            'kategori'  => $j->kategori,
            'lokasi'    => $j->lokasi,
            'tanggal'   => optional($j->tanggal_mulai)->toDateString(),
            'jam_mulai' => $j->jam_mulai,
            'jam_selesai' => $j->jam_selesai,
            'deskripsi' => $j->deskripsi,
        ]);
    }

    public function strukturRT(): JsonResponse
    {
        $pengurus = PengurusRT::orderBy('urutan')->get()->map(fn ($p) => [
            'id'       => $p->id,
            'nama'     => $p->nama,
            'jabatan'  => $p->jabatan,
            'telepon'  => $p->telepon,
            'email'    => $p->email,
            'status'   => $p->status ?? 'aktif',
            'foto_url' => $p->foto ? url($p->foto) : null,
        ]);

        return response()->json(['pengurus' => $pengurus]);
    }

    public function umkm(Request $request): JsonResponse
    {
        $data = UMKM::when($request->filled('cari'), fn ($q) => $q->where('nama_usaha', 'like', '%' . $request->cari . '%'))
            ->latest('id')
            ->paginate(15);

        return $this->halaman($data, fn ($u) => [
            'id'         => $u->id,
            'nama'       => $u->nama_usaha,
            'kategori'   => $u->kategori,
            'deskripsi'  => $u->deskripsi_usaha,
            'produk'     => $u->produk_layanan,
            'alamat'     => $u->alamat_lokasi,
            'jam'        => $u->jam_operasional,
            'telepon'    => $u->no_telepon,
            'whatsapp'   => $u->whatsapp,
            'instagram'  => $u->instagram,
            'foto_url'   => $u->foto_usaha ? url($u->foto_usaha) : null,
        ]);
    }

    /** Bentuk halaman seragam supaya aplikasi punya satu cara memuat lanjutan. */
    private function halaman($paginator, callable $peta): JsonResponse
    {
        return response()->json([
            'data' => collect($paginator->items())->map($peta)->values(),
            'halaman' => [
                'saat_ini' => $paginator->currentPage(),
                'terakhir' => $paginator->lastPage(),
                'total'    => $paginator->total(),
            ],
        ]);
    }
}
