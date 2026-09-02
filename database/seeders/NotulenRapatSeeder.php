<?php

namespace Database\Seeders;

use App\Models\NotulenRapat;
use App\Models\NotulenHadir;
use App\Models\NotulenPoin;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotulenRapatSeeder extends Seeder
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

        // 1. Rapat Evaluasi Keamanan Lingkungan RT 05
        $n1 = NotulenRapat::create([
            'judul_rapat' => 'Rapat Evaluasi Keamanan Lingkungan RT 05',
            'tanggal' => '2025-07-15',
            'waktu_mulai' => '19:30',
            'waktu_selesai' => '21:00',
            'tempat' => 'Balai Warga RT 05',
            'tim_proyek' => 'Keamanan',
            'moderator' => 'Bapak Suharto',
            'notulis' => 'Ibu Wulandari',
            'catatan' => "Kesimpulan rapat:\n1. Perlu penambahan lampu penerangan di gang utama\n2. Ronda malam akan ditambah menjadi 3 pos\n3. Koordinasi dengan Polsek untuk patroli rutin\n\nTindak lanjut:\n- Suharto akan mengajukan anggaran lampu ke RT\n- Wulandari mendata rute ronda baru\n- Rachmat menghubungi Polsek",
            'status' => 'final',
            'dilihat' => 24,
            'user_id' => $admin->id,
        ]);

        NotulenHadir::insert([
            ['notulen_rapat_id' => $n1->id, 'nama_peserta' => 'Bapak Suharto', 'ulasan' => 'Moderator rapat', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'nama_peserta' => 'Bapak Rachmat', 'ulasan' => 'Koordinator keamanan', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'nama_peserta' => 'Ibu Wulandari', 'ulasan' => 'Notulis', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'nama_peserta' => 'Bapak Darmawan', 'ulasan' => '', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'nama_peserta' => 'Ibu Kartini', 'ulasan' => '', 'hadir' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        NotulenPoin::insert([
            ['notulen_rapat_id' => $n1->id, 'topik' => 'Evaluasi jadwal ronda malam bulan ini', 'urutan' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'topik' => 'Penambahan CCTV di ujung gang utama', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'topik' => 'Koordinasi keamanan malam tahun baru', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n1->id, 'topik' => 'Pembentukan tim patrolirwarga', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Rapat Persiapan Kerja Bakti HUT RI
        $n2 = NotulenRapat::create([
            'judul_rapat' => 'Rapat Persiapan Kerja Bakti HUT RI',
            'tanggal' => '2025-07-10',
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '09:30',
            'tempat' => 'Zoom Meeting',
            'tim_proyek' => 'Kebersihan',
            'moderator' => 'Ibu Wulandari',
            'notulis' => 'Ibu Ratna',
            'catatan' => "Rencana kerja bakti:\n- Tanggal: 10 Agustus 2025\n- Waktu: 07:00 - 11:00\n- Kegiatan: Pengecatan gapura, pembersihan jalan, pemasanganumbul-umbul\n\nBagi-bagi tugas per blok sudah didistribusikan",
            'status' => 'final',
            'dilihat' => 18,
            'user_id' => $admin->id,
        ]);

        NotulenHadir::insert([
            ['notulen_rapat_id' => $n2->id, 'nama_peserta' => 'Ibu Wulandari', 'ulasan' => 'Moderator', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'nama_peserta' => 'Ibu Ratna', 'ulasan' => 'Notulis', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'nama_peserta' => 'Bapak Hendra', 'ulasan' => '', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'nama_peserta' => 'Ibu Sari', 'ulasan' => '', 'hadir' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        NotulenPoin::insert([
            ['notulen_rapat_id' => $n2->id, 'topik' => 'Penetapan tanggal dan jam kerja bakti', 'urutan' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'topik' => 'Pembagian tugas per blok', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'topik' => 'Daftar kebutuhan material dan anggaran', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n2->id, 'topik' => 'Koordinasi konsumsi untuk peserta', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Rapat Koordinasi Dana Sosial RT
        $n3 = NotulenRapat::create([
            'judul_rapat' => 'Rapat Koordinasi Dana Sosial RT',
            'tanggal' => '2025-07-05',
            'waktu_mulai' => '19:00',
            'waktu_selesai' => '20:30',
            'tempat' => 'Rumah Ketua RT',
            'tim_proyek' => 'Sosial',
            'moderator' => 'Bapak Suharto',
            'notulis' => 'Ibu Wulandari',
            'catatan' => null,
            'status' => 'final',
            'dilihat' => 15,
            'user_id' => $admin->id,
        ]);

        NotulenPoin::insert([
            ['notulen_rapat_id' => $n3->id, 'topik' => 'Laporan penggunaan dana sosial Q2', 'urutan' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['notulen_rapat_id' => $n3->id, 'topik' => 'Rencana bantuan untuk warga terdampak banjir', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
