<?php

namespace Database\Seeders;

use App\Models\UMKM;
use App\Models\User;
use Illuminate\Database\Seeder;

class UMKMSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::first();
        }

        $umkms = [
            [
                'user_id' => $admin->id,
                'nama_usaha' => 'DEVI Kuliner',
                'kategori' => 'Kuliner',
                'deskripsi_usaha' => 'Usaha sddas asdasd asdasd',
                'produk_layanan' => 'Nasi Goreng, Mie Ayam, Sate',
                'alamat_lokasi' => 'Kemasan',
                'jam_operasional' => 'setiap hari',
                'no_telepon' => '081234567890',
                'whatsapp' => '081234567890',
                'instagram' => 'devikuliner',
                'status' => 'aktif',
            ],
            [
                'user_id' => $admin->id,
                'nama_usaha' => 'Batik Jaya',
                'kategori' => 'Fashion',
                'deskripsi_usaha' => 'Produk batik tulis dan cap berkualitas tinggi dari pengrajin lokal.',
                'produk_layanan' => 'Batik Tulis, Batik Cap, Seragam',
                'alamat_lokasi' => 'Jl. Batik No. 5, RT 02/RW 03',
                'jam_operasional' => 'Senin-Sabtu 08:00-16:00',
                'no_telepon' => '085678901234',
                'whatsapp' => '085678901234',
                'instagram' => 'batikjaya',
                'status' => 'aktif',
            ],
            [
                'user_id' => $admin->id,
                'nama_usaha' => 'Tani Makmur',
                'kategori' => 'Pertanian',
                'deskripsi_usaha' => 'Penjual hasil pertanian segar dari kebun sendiri. Sayuran organik dan buah-buahan lokal.',
                'produk_layanan' => 'Sayuran Organik, Buah Segar, Pupuk',
                'alamat_lokasi' => 'Jl. Pertanian No. 12',
                'jam_operasional' => 'Setiap hari 06:00-10:00',
                'no_telepon' => '087890123456',
                'whatsapp' => '087890123456',
                'instagram' => '',
                'status' => 'aktif',
            ],
        ];

        foreach ($umkms as $data) {
            UMKM::create($data);
        }
    }
}
