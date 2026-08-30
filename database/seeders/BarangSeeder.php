<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\RencanaPembelian;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Sample barang
        $b1 = Barang::create([
            'kode_barang' => 'INV-2026-001',
            'nama_barang' => 'BEI',
            'kategori' => 'Perlengkapan',
            'kondisi' => 'Baik',
            'jumlah' => 1,
            'satuan' => 'unit',
            'lokasi' => 'GUDANG',
            'tanggal_pembelian' => '2026-01-15',
            'harga_pembelian' => 250000,
            'sumber_dana' => 'Kas RT',
            'keterangan' => 'Baki untuk kegiatan arisan',
            'status' => 'aktif',
        ]);

        $b2 = Barang::create([
            'kode_barang' => 'INV-2026-002',
            'nama_barang' => 'TIKAR',
            'kategori' => 'Lainnya',
            'kondisi' => 'Baik',
            'jumlah' => 6,
            'satuan' => 'PCS',
            'lokasi' => 'GUDANG',
            'tanggal_pembelian' => '2026-02-10',
            'harga_pembelian' => 150000,
            'sumber_dana' => 'Kas RT',
            'keterangan' => 'Tikar untuk rapat RT',
            'status' => 'aktif',
        ]);

        $b3 = Barang::create([
            'kode_barang' => 'INV-2026-003',
            'nama_barang' => 'PROYEKTOR EPSON',
            'kategori' => 'Elektronik',
            'kondisi' => 'Baik',
            'jumlah' => 1,
            'satuan' => 'unit',
            'lokasi' => 'Ruang Rapat',
            'tanggal_pembelian' => '2026-03-05',
            'harga_pembelian' => 5000000,
            'sumber_dana' => 'Iuran Khusus',
            'keterangan' => 'Proyektor untuk presentasi rapat RT',
            'status' => 'aktif',
        ]);

        $b4 = Barang::create([
            'kode_barang' => 'INV-2026-004',
            'nama_barang' => 'KURSI LIPAT',
            'kategori' => 'Furniture',
            'kondisi' => 'Baik',
            'jumlah' => 20,
            'satuan' => 'BH',
            'lokasi' => 'GUDANG',
            'tanggal_pembelian' => '2026-04-20',
            'harga_pembelian' => 2000000,
            'sumber_dana' => 'Kas RT',
            'keterangan' => 'Kursi lipat untuk acara',
            'status' => 'aktif',
        ]);

        $b5 = Barang::create([
            'kode_barang' => 'INV-2026-005',
            'nama_barang' => 'SPEAKER PORTABLE',
            'kategori' => 'Elektronik',
            'kondisi' => 'Perlu Perbaikan',
            'jumlah' => 1,
            'satuan' => 'unit',
            'lokasi' => 'GUDANG',
            'tanggal_pembelian' => '2026-05-12',
            'harga_pembelian' => 750000,
            'sumber_dana' => 'Kas RT',
            'keterangan' => 'Speaker untuk pengumuman, perlu servis',
            'status' => 'aktif',
        ]);

        // Sample rencana pembelian
        RencanaPembelian::create([
            'kode_rencana' => 'RP2026030001',
            'nama_barang' => 'TANGGA',
            'kategori' => 'Perlengkapan',
            'jumlah' => 1,
            'satuan' => 'unit',
            'prioritas' => 'sedang',
            'estimasi_harga' => 150000,
            'sumber_dana' => 'Kas RT',
            'tanggal_rencana' => '2026-03-04',
            'keterangan' => 'Tangga untuk perbaikan lampu jalan',
            'status' => 'terbeli',
            'barang_id' => null,
        ]);

        RencanaPembelian::create([
            'kode_rencana' => 'RP2026040001',
            'nama_barang' => 'KERTAS A4',
            'kategori' => 'ATK',
            'jumlah' => 5,
            'satuan' => 'rim',
            'prioritas' => 'tinggi',
            'estimasi_harga' => 55000,
            'sumber_dana' => 'Kas RT',
            'tanggal_rencana' => '2026-04-15',
            'keterangan' => 'Kertas untuk cetak surat undangan',
            'status' => 'direncanakan',
        ]);
    }
}
