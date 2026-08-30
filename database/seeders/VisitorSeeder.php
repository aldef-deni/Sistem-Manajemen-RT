<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        Visitor::create([
            'kode_kunjungan' => 'VIS-260316001',
            'tipe_kunjungan' => 'singkat',
            'nama_tamu' => 'ARIP',
            'nik' => null,
            'no_hp' => '085876460356',
            'email' => null,
            'no_plat' => 'K 3257 YF',
            'jenis_kendaraan' => 'Motor',
            'tujuan_blok' => 'BLOK A',
            'nama_tujuan' => null,
            'kepentingan' => ['Kunjungan Biasa'],
            'deskripsi_kepentingan' => 'ANTAR PAKET',
            'catatan_tambahan' => null,
            'foto_dokumentasi' => null,
            'tipe_foto' => null,
            'wa_host' => '085876460356',
            'jam_checkin' => '13.00',
            'jam_checkout' => '13.01',
            'tanggal' => '2026-03-16',
            'durasi' => '1 menit',
            'status' => 'checkout',
        ]);

        Visitor::create([
            'kode_kunjungan' => 'VIS-260831001',
            'tipe_kunjungan' => 'singkat',
            'nama_tamu' => 'SITI RAHMA',
            'nik' => null,
            'no_hp' => '081234567890',
            'email' => null,
            'no_plat' => 'AD 1234 AB',
            'jenis_kendaraan' => 'Mobil',
            'tujuan_blok' => 'BLOK B',
            'nama_tujuan' => 'Budi Santoso',
            'kepentingan' => ['Kunjungan Biasa'],
            'deskripsi_kepentingan' => 'KUNJUNGAN KELUARGA',
            'catatan_tambahan' => null,
            'foto_dokumentasi' => null,
            'tipe_foto' => 'Foto Wajah',
            'wa_host' => '081298765432',
            'jam_checkin' => '10.30',
            'jam_checkout' => null,
            'tanggal' => date('Y-m-d'),
            'durasi' => null,
            'status' => 'checkin',
        ]);
    }
}
