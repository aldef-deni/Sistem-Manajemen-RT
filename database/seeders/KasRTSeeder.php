<?php

namespace Database\Seeders;

use App\Models\RekeningKas;
use App\Models\TransaksiKas;
use App\Models\User;
use Illuminate\Database\Seeder;

class KasRTSeeder extends Seeder
{
    public function run(): void
    {
        $rekenings = [
            ['nama' => 'TAB-BRI', 'jenis' => 'TABUNGAN', 'saldo' => 50000],
            ['nama' => 'BRI', 'jenis' => 'BANK', 'saldo' => 0],
            ['nama' => 'BCA', 'jenis' => 'BANK', 'saldo' => 0],
            ['nama' => 'MANDIRI', 'jenis' => 'BANK', 'saldo' => 0],
            ['nama' => 'TUNAI', 'jenis' => 'TUNAI', 'saldo' => 150000],
            ['nama' => 'DANA', 'jenis' => 'E-WALLET', 'saldo' => 0],
        ];

        $admin = User::where('email', 'admin@sistemrt.com')->first() ?? User::first();

        foreach ($rekenings as $rk) {
            RekeningKas::create($rk);
        }

        // Sample transactions
        $tabBri = RekeningKas::where('nama', 'TAB-BRI')->first();
        $tunai = RekeningKas::where('nama', 'TUNAI')->first();

        $transaksi = [
            [
                'tanggal' => '2026-03-16',
                'jenis' => 'masuk',
                'kategori' => 'Setoran Tabungan',
                'rekening_kas_id' => $tabBri->id,
                'nominal' => 50000,
                'keterangan' => 'Setoran tabungan a.n. Budi Sari (Tabungan ID: 12)',
                'user_id' => $admin->id,
            ],
            [
                'tanggal' => '2026-03-20',
                'jenis' => 'masuk',
                'kategori' => 'Iuran Kebersihan',
                'rekening_kas_id' => $tunai->id,
                'nominal' => 30000,
                'keterangan' => 'Iuran kebersihan bulan Maret',
                'user_id' => $admin->id,
            ],
            [
                'tanggal' => '2026-04-05',
                'jenis' => 'masuk',
                'kategori' => 'Iuran Keamanan',
                'rekening_kas_id' => $tunai->id,
                'nominal' => 25000,
                'keterangan' => 'Iuran keamanan bulan April',
                'user_id' => $admin->id,
            ],
            [
                'tanggal' => '2026-04-10',
                'jenis' => 'keluar',
                'kategori' => 'Beli Alat Kebersihan',
                'rekening_kas_id' => $tunai->id,
                'nominal' => 15000,
                'keterangan' => 'Pembelian sapu lidi dan kantong sampah',
                'user_id' => $admin->id,
            ],
            [
                'tanggal' => '2026-05-01',
                'jenis' => 'masuk',
                'kategori' => 'Setoran Tabungan',
                'rekening_kas_id' => $tabBri->id,
                'nominal' => 50000,
                'keterangan' => 'Setoran tabungan a.n. Rina Wati (Tabungan ID: 8)',
                'user_id' => $admin->id,
            ],
            [
                'tanggal' => '2026-05-15',
                'jenis' => 'keluar',
                'kategori' => 'Operasional RT',
                'rekening_kas_id' => $tunai->id,
                'nominal' => 10000,
                'keterangan' => 'Biaya listrik pos ronda bulan Mei',
                'user_id' => $admin->id,
            ],
        ];

        foreach ($transaksi as $t) {
            TransaksiKas::create($t);
        }
    }
}
