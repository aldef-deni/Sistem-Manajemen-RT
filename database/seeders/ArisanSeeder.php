<?php

namespace Database\Seeders;

use App\Models\Arisan;
use App\Models\AnggotaKeluarga;
use App\Models\RekeningKas;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArisanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@rt05.com')->first();
        $tunai = RekeningKas::where('nama', 'TUNAI')->first();

        // Arisan Mingguan
        $arisan1 = Arisan::create([
            'nama' => 'ARISAN DALAMs',
            'nominal_iuran' => 150000,
            'tanggal_mulai' => '2026-03-15',
            'periode' => 'mingguan',
            'mode_undian' => 'manual',
            'jumlah_pemenang_per_pertemuan' => 1,
            'rekening_kas_id' => $tunai?->id,
            'keterangan' => 'Arisan mingguan warga RT 05',
            'status' => 'aktif',
        ]);

        // Arisan Bulanan
        $arisan2 = Arisan::create([
            'nama' => 'ARISAN BULANAN',
            'nominal_iuran' => 35000,
            'tanggal_mulai' => '2026-03-13',
            'periode' => 'bulanan',
            'mode_undian' => 'otomatis',
            'jumlah_pemenang_per_pertemuan' => 1,
            'rekening_kas_id' => $tunai?->id,
            'keterangan' => 'Arisan bulanan warga RT 05',
            'status' => 'aktif',
        ]);

        // Tambah peserta
        $wargas = AnggotaKeluarga::with('kartuKeluarga')->get();

        // Arisan mingguan: 3 peserta
        $wargas->slice(0, 3)->each(function ($w) use ($arisan1) {
            $arisan1->peserta()->attach($w->id, [
                'urutan' => $arisan1->peserta()->count() + 1,
            ]);
        });

        // Arisan bulanan: semua warga yang ada
        $wargas->each(function ($w) use ($arisan2) {
            $arisan2->peserta()->attach($w->id, [
                'urutan' => $arisan2->peserta()->count() + 1,
            ]);
        });
    }
}
