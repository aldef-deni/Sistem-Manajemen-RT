<?php

namespace Database\Seeders;

use App\Models\PeminjamanBarang;
use App\Models\Barang;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $tikar = Barang::where('nama_barang', 'TIKAR')->first();

        if ($tikar) {
            // Peminjaman sudah dikembalikan
            PeminjamanBarang::create([
                'kode_peminjaman' => 'PJM-2026020001',
                'barang_id' => $tikar->id,
                'jumlah_pinjam' => 1,
                'kondisi_saat_pinjam' => 'Baik',
                'tanggal_pinjam' => '2026-02-26',
                'tanggal_rencana_kembali' => '2026-03-14',
                'tanggal_kembali' => '2026-03-12',
                'keperluan' => 'Untuk acara arisan',
                'nama_peminjam' => 'Bunga Citra',
                'no_hp_peminjam' => '0812-3456-7890',
                'status' => 'dikembalikan',
                'kondisi_saat_kembali' => 'Baik',
            ]);

            PeminjamanBarang::create([
                'kode_peminjaman' => 'PJM-2026020002',
                'barang_id' => $tikar->id,
                'jumlah_pinjam' => 2,
                'kondisi_saat_pinjam' => 'Baik',
                'tanggal_pinjam' => '2026-02-26',
                'tanggal_rencana_kembali' => '2026-02-25',
                'tanggal_kembali' => '2026-02-25',
                'keperluan' => 'Untuk rapat RT',
                'nama_peminjam' => 'Budi Santoso',
                'no_hp_peminjam' => '0856-7890-1234',
                'status' => 'dikembalikan',
                'kondisi_saat_kembali' => 'Baik',
            ]);

            PeminjamanBarang::create([
                'kode_peminjaman' => 'PJM-2026020003',
                'barang_id' => $tikar->id,
                'jumlah_pinjam' => 1,
                'kondisi_saat_pinjam' => 'Baik',
                'tanggal_pinjam' => '2026-02-27',
                'tanggal_rencana_kembali' => '2026-03-14',
                'tanggal_kembali' => '2026-03-10',
                'keperluan' => 'Untuk pengajian',
                'nama_peminjam' => 'Ani Suryani',
                'no_hp_peminjam' => '0878-9012-3456',
                'status' => 'dikembalikan',
                'kondisi_saat_kembali' => 'Baik',
            ]);
        }
    }
}
