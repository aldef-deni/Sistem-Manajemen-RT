<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\IuranWarga;
use App\Models\JenisIuran;
use Illuminate\Database\Seeder;

class IuranWargaSeeder extends Seeder
{
    public function run(): void
    {
        // Create Jenis Iuran
        $jenisIuranData = [
            ['nama' => 'Iuran Kebersihan', 'nominal_default' => 30000, 'deskripsi' => 'Iuran bulanan untuk kebersihan lingkungan RT'],
            ['nama' => 'Iuran Keamanan', 'nominal_default' => 25000, 'deskripsi' => 'Iuran bulanan untuk keamanan lingkungan RT'],
            ['nama' => 'Iuran Sosial', 'nominal_default' => 20000, 'deskripsi' => 'Iuran bulanan untuk kegiatan sosial RT'],
            ['nama' => 'Iuran Kas RT', 'nominal_default' => 50000, 'deskripsi' => 'Setoran kas RT bulanan'],
            ['nama' => 'Iuran Pembangunan', 'nominal_default' => 100000, 'deskripsi' => 'Iuran untuk pembangunan fasilitas RT'],
        ];

        $jenisIurans = [];
        foreach ($jenisIuranData as $data) {
            $jenisIurans[] = JenisIuran::create($data);
        }

        // Get all anggota
        $anggotaList = AnggotaKeluarga::all();

        // Create iuran for each warga for the last 3 months
        $bulan = (int) now()->format('m');
        $tahun = (int) now()->format('Y');

        foreach ($anggotaList as $anggota) {
            foreach ($jenisIurans as $ji) {
                // Create for last 3 months
                for ($i = 0; $i < 3; $i++) {
                    $m = $bulan - $i;
                    $y = $tahun;
                    if ($m <= 0) {
                        $m += 12;
                        $y--;
                    }

                    // Random status: 60% lunas, 40% belum bayar
                    $status = rand(1, 100) <= 60 ? 'lunas' : 'belum_bayar';

                    IuranWarga::create([
                        'anggota_keluarga_id' => $anggota->id,
                        'jenis_iuran_id' => $ji->id,
                        'bulan' => $m,
                        'tahun' => $y,
                        'nominal' => $ji->nominal_default,
                        'status' => $status,
                        'tanggal_bayar' => $status === 'lunas' ? now()->subDays(rand(1, 20)) : null,
                        'catatan' => null,
                    ]);
                }
            }
        }
    }
}
