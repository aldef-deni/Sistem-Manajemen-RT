<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\RekeningKas;
use App\Models\Tabungan;
use App\Models\TabunganTransaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TabunganSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sistemrt.com')->first() ?? User::first();
        $tabBri = RekeningKas::where('nama', 'TAB-BRI')->first();
        $tunai = RekeningKas::where('nama', 'TUNAI')->first();

        // Create tabungan for first few warga
        $wargas = AnggotaKeluarga::with('kartuKeluarga')->limit(5)->get();

        foreach ($wargas as $idx => $w) {
            $tab = Tabungan::create([
                'anggota_keluarga_id' => $w->id,
                'no_rekening' => 'TBG-' . strtoupper(Str::random(8)),
                'jenis_tabungan' => 'sukarela',
                'saldo' => $idx === 0 ? 50000 : 0,
                'status' => 'aktif',
            ]);

            // Add a setoran for the first warga
            if ($idx === 0 && $tabBri) {
                TabunganTransaksi::create([
                    'tabungan_id' => $tab->id,
                    'jenis' => 'setoran',
                    'nominal' => 50000,
                    'saldo_sebelum' => 0,
                    'saldo_sesudah' => 50000,
                    'rekening_kas_id' => $tabBri->id,
                    'keterangan' => 'Setoran awal tabungan sukarela',
                    'status' => 'dikonfirmasi',
                    'user_id' => $admin->id,
                ]);
            }
        }
    }
}
