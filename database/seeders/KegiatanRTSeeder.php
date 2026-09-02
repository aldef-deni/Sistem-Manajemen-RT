<?php

namespace Database\Seeders;

use App\Models\KegiatanRT;
use App\Models\User;
use Illuminate\Database\Seeder;

class KegiatanRTSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@sistemrt.com'],
            ['name' => 'Administrator', 'password' => bcrypt('password')]
        );

        $kegiatans = [
            [
                'judul' => 'Gotong Royong Bulanan',
                'artikel' => '<p>Kegiatan gotong royong bulanan RT 05 akan dilaksanakan pada hari Minggu pagi. Seluruh warga diharapkan hadir untuk membersihkan lingkungan sekitar.</p><p>Perlengkapan yang perlu dibawa:</p><ul><li>Cangkul dan alat berkebun</li><li>Sapu lidi</li><li>Karung untuk sampah</li></ul><p>Mari kita jaga kebersihan lingkungan bersama-sama!</p>',
                'kategori' => 'Kebersihan',
                'status' => 'publish',
                'tanggal_mulai' => '2026-09-07',
                'lokasi' => 'Lingkungan RT 05',
                'dilihat' => 45,
            ],
            [
                'judul' => 'Ronda Malam Jumat',
                'artikel' => '<p>Jadwal ronda malam Jumat untuk menjaga keamanan lingkungan RT 05. Petugas yang bertugas diharapkan hadir tepat waktu.</p><p>Pos ronda akan aktif mulai pukul 21.00 hingga 05.00 WIB.</p>',
                'kategori' => 'Keamanan',
                'status' => 'publish',
                'tanggal_mulai' => '2026-09-05',
                'lokasi' => 'Pos RT 05',
                'dilihat' => 32,
            ],
            [
                'judul' => 'Senam Pagi Bersama',
                'artikel' => '<p>Ayo ikuti senam pagi bersama setiap hari Sabtu pukul 06.30 WIB. Kegiatan ini terbuka untuk seluruh warga RT 05.</p><p>Manfaat senam pagi antara lain:</p><ul><li>Menjaga kesehatan jantung</li><li>Meningkatkan kebugaran tubuh</li><li>Mempererat tali silaturahmi</li></ul>',
                'kategori' => 'Olahraga',
                'status' => 'publish',
                'tanggal_mulai' => '2026-09-06',
                'lokasi' => 'Halaman Balai Warga',
                'dilihat' => 28,
            ],
            [
                'judul' => 'Pengajian Rutin Rabu Malam',
                'artikel' => '<p>Pengajian rutin setiap Rabu malam pukul 19.30 WIB. Tema bulan ini: "Menjaga Ukhuwah dalam Bermasyarakat".</p><p>Ust. Ahmad akan menjadi pembimbing.</p>',
                'kategori' => 'Keagamaan',
                'status' => 'draft',
                'tanggal_mulai' => '2026-09-10',
                'lokasi' => 'Mushola Al-Hikmah',
                'dilihat' => 0,
            ],
        ];

        foreach ($kegiatans as $k) {
            KegiatanRT::create(array_merge($k, ['user_id' => $admin->id]));
        }
    }
}
