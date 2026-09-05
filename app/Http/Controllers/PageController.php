<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    protected array $pages = [
        // Kependudukan
        'data-warga'        => ['title' => 'Data Warga',        'section' => 'Kependudukan'],
        'kartu-keluarga'    => ['title' => 'Kartu Keluarga',    'section' => 'Kependudukan'],

        // Keuangan
        'iuran-warga'       => ['title' => 'Iuran Warga',       'section' => 'Keuangan'],
        'kas-rt'            => ['title' => 'Kas RT',            'section' => 'Keuangan'],
        'pembayaran'        => ['title' => 'Pembayaran',        'section' => 'Keuangan'],
        'laporan-keuangan'  => ['title' => 'Laporan Keuangan',  'section' => 'Keuangan'],
        'arisan-rt'         => ['title' => 'Arisan RT',         'section' => 'Keuangan'],

        // Inventaris
        'data-barang'       => ['title' => 'Data Barang',       'section' => 'Inventaris'],

        // Dokumentasi
        'layanan-warga'     => ['title' => 'Layanan Warga',     'section' => 'Dokumentasi'],
        'layanan'           => ['title' => 'Layanan',           'section' => 'Dokumentasi'],
        'surat-menunggu'    => ['title' => 'Surat Menunggu',    'section' => 'Dokumentasi'],
        'e-ktp'             => ['title' => 'e-KTP',             'section' => 'Dokumentasi'],
        'buat-pengajuan'    => ['title' => 'Buat Pengajuan',    'section' => 'Dokumentasi'],

        // Pengaturan & Info
        'pengaturan'        => ['title' => 'Pengaturan',        'section' => 'Pengaturan & Info'],
        'kalender'          => ['title' => 'Kalender',          'section' => 'Pengaturan & Info'],
        'jadwal-keamanan'   => ['title' => 'Jadwal Keamanan',   'section' => 'Pengaturan & Info'],

        // Keamanan & Kebersihan
        'patroli-rt'        => ['title' => 'Patroli RT',        'section' => 'Keamanan & Kebersihan'],
        'ronda-rapat'       => ['title' => 'Ronda Rapat',       'section' => 'Keamanan & Kebersihan'],
        'struktur-rt'       => ['title' => 'Struktur RT',       'section' => 'Keamanan & Kebersihan'],


        // Apresiasi & Partisipasi
        'penghargaan'       => ['title' => 'Penghargaan',       'section' => 'Apresiasi & Partisipasi'],
        'voting-warga'      => ['title' => 'Voting Warga',      'section' => 'Apresiasi & Partisipasi'],

        // Profil
        'profil-saya'       => ['title' => 'Profil Saya',       'section' => 'Akun'],
    ];

    public function show(Request $request, ?string $page = null)
    {
        // Rute halaman ini didaftarkan sebagai URL literal tanpa parameter,
        // jadi nama halaman diambil dari path permintaan.
        $page ??= $request->path();

        if (!isset($this->pages[$page])) {
            abort(404);
        }

        $data = $this->pages[$page];

        return view('pages.coming-soon', [
            'title'   => $data['title'],
            'section' => $data['section'],
        ]);
    }
}
