<?php

namespace Database\Seeders;

use App\Models\JenisPinjaman;
use Illuminate\Database\Seeder;

class PinjamanSeeder extends Seeder
{
    public function run(): void
    {
        JenisPinjaman::insert([
            [
                'nama' => 'Pinjaman Dana Darurat',
                'bunga_persen' => 2.00,
                'denda_persen' => 1.00,
                'tenor_bulan' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pinjaman Usaha',
                'bunga_persen' => 1.50,
                'denda_persen' => 1.00,
                'tenor_bulan' => 12,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pinjaman Konsumtif',
                'bunga_persen' => 2.50,
                'denda_persen' => 1.00,
                'tenor_bulan' => 6,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
