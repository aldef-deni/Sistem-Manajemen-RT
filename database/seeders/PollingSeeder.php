<?php

namespace Database\Seeders;

use App\Models\Polling;
use App\Models\PollingVote;
use App\Models\User;
use Illuminate\Database\Seeder;

class PollingSeeder extends Seeder
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

        // Create a few extra users to simulate voters
        $voters = [];
        foreach (['Budi Santoso', 'Ani Suryani', 'Dedi Kurniawan', 'Siti Rahma'] as $i => $nama) {
            $voters[] = User::firstOrCreate(
                ['name' => $nama],
                ['email' => 'warga' . ($i + 2) . '@sistemrt.com', 'password' => bcrypt('password')]
            );
        }

        // 1. Pembelian Karpet
        $p1 = Polling::create([
            'user_id' => $admin->id,
            'judul' => 'Pembelian Karpet',
            'deskripsi' => 'Ada wacana pembelian karpet untuk aula RT. Kira-kira setuju tidak?',
            'tanggal_mulai' => '2026-02-23',
            'tanggal_selesai' => '2026-03-14',
            'opsi' => ['Setuju', 'Tidak', 'Golput'],
            'tampilkan_hasil' => true,
            'izinkan_ganti' => false,
            'anonim' => false,
            'status' => 'aktif',
            'jumlah_suara' => 3,
        ]);

        PollingVote::insert([
            ['polling_id' => $p1->id, 'user_id' => $admin->id, 'pilihan' => 'Setuju', 'created_at' => now(), 'updated_at' => now()],
            ['polling_id' => $p1->id, 'user_id' => $voters[0]->id, 'pilihan' => 'Setuju', 'created_at' => now(), 'updated_at' => now()],
            ['polling_id' => $p1->id, 'user_id' => $voters[1]->id, 'pilihan' => 'Tidak', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Jam Ronda Malam
        $p2 = Polling::create([
            'user_id' => $admin->id,
            'judul' => 'Penentuan Jam Ronda Malam',
            'deskripsi' => 'Pilih jam mulai ronda malam yang paling disetujui warga.',
            'tanggal_mulai' => '2026-01-10',
            'tanggal_selesai' => '2026-01-20',
            'opsi' => ['19:00', '20:00', '21:00', '22:00'],
            'tampilkan_hasil' => true,
            'izinkan_ganti' => true,
            'anonim' => false,
            'status' => 'selesai',
            'jumlah_suara' => 4,
        ]);

        PollingVote::insert([
            ['polling_id' => $p2->id, 'user_id' => $admin->id, 'pilihan' => '21:00', 'created_at' => now(), 'updated_at' => now()],
            ['polling_id' => $p2->id, 'user_id' => $voters[0]->id, 'pilihan' => '20:00', 'created_at' => now(), 'updated_at' => now()],
            ['polling_id' => $p2->id, 'user_id' => $voters[1]->id, 'pilihan' => '21:00', 'created_at' => now(), 'updated_at' => now()],
            ['polling_id' => $p2->id, 'user_id' => $voters[2]->id, 'pilihan' => '19:00', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}