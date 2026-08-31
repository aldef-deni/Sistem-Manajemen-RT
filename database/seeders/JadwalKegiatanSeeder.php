<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalKegiatan;
use App\Models\AnggotaKeluarga;
use App\Models\User;
use Carbon\Carbon;

class JadwalKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'Administrator')->first();
        $warga = AnggotaKeluarga::first();

        if (!$warga) return;

        $jadwalList = [
            [
                'nama_kegiatan' => 'Ronda Malam',
                'kategori' => 'Keamanan',
                'jenis_jadwal' => 'Harian',
                'lokasi' => 'Pos RT 001',
                'penanggung_jawab_id' => $warga->id,
                'deskripsi' => 'Patroli keamanan malam hari untuk menjaga ketertiban lingkungan RT.',
                'petugas' => ['Blok A', 'Blok B', 'Blok C'],
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'jam_mulai' => '21:00',
                'jam_selesai' => '05:00',
                'status' => 'aktif',
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'nama_kegiatan' => 'Gotong Royong Bersih-bersih',
                'kategori' => 'Kebersihan',
                'jenis_jadwal' => 'Mingguan',
                'lokasi' => 'Area RT 001',
                'penanggung_jawab_id' => $warga->id,
                'deskripsi' => 'Kegiatan bersih-bersih lingkungan RT setiap hari Minggu pagi.',
                'petugas' => ['Semua Warga'],
                'tanggal_mulai' => Carbon::now()->subDays(14),
                'jam_mulai' => '07:00',
                'jam_selesai' => '10:00',
                'status' => 'aktif',
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'nama_kegiatan' => 'Pengajian Bulanan',
                'kategori' => 'Keagamaan',
                'jenis_jadwal' => 'Bulanan',
                'lokasi' => 'Balai Warga RT 001',
                'penanggung_jawab_id' => $warga->id,
                'deskripsi' => 'Pengajian rutin bulanan untuk seluruh warga Muslim.',
                'petugas' => ['Ibu-ibu PKK', 'Remaja Masjid'],
                'tanggal_mulai' => Carbon::now()->addDays(5),
                'jam_mulai' => '19:30',
                'jam_selesai' => '21:00',
                'status' => 'aktif',
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'nama_kegiatan' => 'Senam Pagi',
                'kategori' => 'Olahraga',
                'jenis_jadwal' => 'Mingguan',
                'lokasi' => 'Halaman RT 001',
                'penanggung_jawab_id' => $warga->id,
                'deskripsi' => 'Senam pagi bersama setiap hari Sabtu.',
                'petugas' => ['Warga Perempuan'],
                'tanggal_mulai' => Carbon::now()->subDays(7),
                'jam_mulai' => '06:30',
                'jam_selesai' => '07:30',
                'status' => 'aktif',
                'dibuat_oleh' => $admin?->id,
            ],
        ];

        foreach ($jadwalList as $j) {
            JadwalKegiatan::create($j);
        }
    }
}
