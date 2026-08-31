<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'Administrator')->first();

        $pengumumanList = [
            [
                'judul' => 'Pembayaran Iuran Bulanan Agustus 2026',
                'kategori' => 'Keuangan',
                'target' => 'semua',
                'isi' => '<h3>Pembayaran Iuran Agustus 2026</h3><p>Kepada Yth. Seluruh Warga RT 001,</p><p>Dengan ini kami informasikan bahwa pembayaran iuran bulanan untuk bulan <strong>Agustus 2026</strong> sudah dapat dilakukan.</p><h4>Ketentuan:</h4><ul><li>Iuran warga: <strong>Rp 50.000/bulan</strong></li><li>Batas pembayaran: <strong>20 Agustus 2026</strong></li><li>Waktu pembayaran: 08.00 - 17.00 WIB</li></ul><p>Mohon untuk segera melakukan pembayaran agar administrasi RT berjalan lancar. Terima kasih.</p>',
                'tanggal_publish' => Carbon::now()->subDays(5),
                'tanggal_berakhir' => Carbon::now()->addDays(15),
                'status' => 'publish',
                'dilihat' => 45,
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'judul' => 'Jadwal Patroli Keamanan Malam Hari',
                'kategori' => 'Keamanan',
                'target' => 'semua',
                'isi' => '<p>Bagi warga yang mendapatkan jadwal ronda malam, mohon untuk dapat menjalankan tugas dengan baik.</p><p><strong>Jadwal minggu ini:</strong></p><ul><li>Senin - Rabu: Blok A & B</li><li>Kamis - Sabtu: Blok C & D</li><li>Minggu: Jaga Pintu Utama</li></ul><p>Terima kasih atas partisipasinya.</p>',
                'tanggal_publish' => Carbon::now()->subDays(3),
                'tanggal_berakhir' => Carbon::now()->addDays(4),
                'status' => 'publish',
                'dilihat' => 32,
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'judul' => 'Gotong Royong Bersih-Bersih Lingkungan',
                'kategori' => 'Kebersihan',
                'target' => 'semua',
                'isi' => '<p>Ayo kita bersama-sama menjaga kebersihan lingkungan RT!</p><p>Diinformasikan akan diadakan kegiatan <strong>Gotong Royong Bersih-Bersih Lingkungan</strong> pada:</p><ul><li><strong>Hari:</strong> Minggu, 7 September 2026</li><li><strong>Waktu:</strong> 07.00 - 10.00 WIB</li><li><strong>Titik Kumpul:</strong> Halaman Balai RT</li></ul><p>Mohon partisipasi seluruh warga. Untuk yang berhalangan, dapat menghubungi ketua RT.</p>',
                'tanggal_publish' => Carbon::now()->subDays(2),
                'tanggal_berakhir' => Carbon::parse('2026-09-07'),
                'status' => 'publish',
                'dilihat' => 28,
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'judul' => 'Undangan Rapat RT Bulanan',
                'kategori' => 'Kegiatan',
                'target' => 'rt',
                'isi' => '<p>Kepada seluruh pengurus RT 001,</p><p>Akan diadakan <strong>Rapat Bulanan RT</strong> dengan agenda:</p><ol><li>Evaluasi kegiatan bulan Agustus</li><li>Rencana kegiatan bulan September</li><li>Pembahasan anggaran</li><li>Saran dan masukan warga</li></ol><p><strong>Jadwal:</strong></p><ul><li>Hari: Jumat, 5 September 2026</li><li>Waktu: 19.30 WIB</li><li>Tempat: Balai RT</li></ul>',
                'tanggal_publish' => Carbon::now()->subDay(),
                'tanggal_berakhir' => Carbon::parse('2026-09-05'),
                'status' => 'publish',
                'dilihat' => 15,
                'dibuat_oleh' => $admin?->id,
            ],
            [
                'judul' => 'Pengumuman Pemenang Arisan Bulanan',
                'kategori' => 'Umum',
                'target' => 'semua',
                'isi' => '<p>Selamat kepada pemenang arisan bulanan Agustus 2026!</p><p>Pemenang undian adalah <strong>Ibu Siti Aminah</strong> dari Blok B.</p><p>Selanjutnya arisan akan dilanjutkan untuk periode September 2026. Bagi peserta yang belum membayar iuran, mohon segera menyelesaikan pembayaran.</p>',
                'tanggal_publish' => now()->subHours(6),
                'status' => 'draft',
                'dilihat' => 0,
                'dibuat_oleh' => $admin?->id,
            ],
        ];

        foreach ($pengumumanList as $p) {
            Pengumuman::create($p);
        }
    }
}
