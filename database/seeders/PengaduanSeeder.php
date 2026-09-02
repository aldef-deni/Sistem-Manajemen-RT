<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\PengaduanBalasan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('name', 'Administrator')->first();
        if (!$admin) {
            $admin = User::firstOrCreate(
                ['name' => 'Administrator'],
                ['email' => 'admin@sistemrt.com', 'password' => bcrypt('password'), 'role' => 'admin']
            );
        }

        // 1. Pengaduan Bising
        $p1 = Pengaduan::create([
            'kode_tiket' => 'TKT260223001',
            'user_id' => $admin->id,
            'judul' => 'Bising',
            'kategori' => 'Keamanan',
            'isi_pengaduan' => 'Suara bising dari warung di sebelah barat sangat mengganggu ketenangan warga pada malam hari. Sudah beberapa kali warga mengeluhkan hal ini.',
            'privasi' => 'publik',
            'status' => 'selesai',
            'balasan' => 'Sudah ditindaklanjuti oleh tim keamanan RT.',
            'dibalas_oleh' => 'Administrator',
            'tanggal_balas' => now()->subDays(5),
        ]);

        PengaduanBalasan::create([
            'pengaduan_id' => $p1->id,
            'user_id' => $admin->id,
            'pesan' => 'Sudah ditindaklanjuti oleh tim keamanan RT.',
        ]);

        // 2. Pengaduan Sampah
        $p2 = Pengaduan::create([
            'kode_tiket' => 'TKT260301001',
            'user_id' => $admin->id,
            'judul' => 'Sampah Menumpuk di Gang 3',
            'kategori' => 'Kebersihan',
            'isi_pengaduan' => 'Sampah menumpuk di depan gang 3 sudah 3 hari tidak diangkut. Hal ini mulai menimbulkan bau tidak sedap dan mengundang lalat.',
            'privasi' => 'publik',
            'status' => 'diproses',
        ]);

        // 3. Pengaduan Lampu Mati
        $p3 = Pengaduan::create([
            'kode_tiket' => 'TKT260302001',
            'user_id' => $admin->id,
            'judul' => 'Lampu Jalan Mati di Blok B',
            'kategori' => 'Infrastruktur',
            'isi_pengaduan' => 'Lampu penerangan jalan di Blok B sudah mati sejak seminggu yang lalu. Keadaan menjadi gelap dan tidak aman pada malam hari.',
            'privasi' => 'privat',
            'status' => 'diterima',
        ]);

        // 4. Pengaduan Iuran
        $p4 = Pengaduan::create([
            'kode_tiket' => 'TKT260303001',
            'user_id' => $admin->id,
            'judul' => 'Pertanyaan Tentang Iuran Bulanan',
            'kategori' => 'Keuangan',
            'isi_pengaduan' => 'Mohon penjelasan mengenai rincian penggunaan iuran bulanan bulan lalu. Apakah ada laporan transparan untuk warga?',
            'privasi' => 'publik',
            'status' => 'selesai',
            'balasan' => 'Laporan keuangan sudah dipublish di pengumuman. Silakan cek.',
            'dibalas_oleh' => 'Administrator',
            'tanggal_balas' => now()->subDays(3),
        ]);

        PengaduanBalasan::create([
            'pengaduan_id' => $p4->id,
            'user_id' => $admin->id,
            'pesan' => 'Laporan keuangan sudah dipublish di pengumuman. Silakan cek.',
        ]);
    }
}
