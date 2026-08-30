<?php

namespace Database\Seeders;

use App\Models\PenerimaBantuan;
use App\Models\PengajuanKurangMampu;
use App\Models\AnggotaKeluarga;
use Illuminate\Database\Seeder;

class BantuanSosialSeeder extends Seeder
{
    public function run(): void
    {
        $warga = AnggotaKeluarga::with('kartuKeluarga')->first();
        if (!$warga) return;

        // Sample penerima bantuan
        PenerimaBantuan::create([
            'anggota_keluarga_id' => $warga->id,
            'nik' => $warga->nik,
            'no_kk' => $warga->kartuKeluarga->no_kk ?? null,
            'jenis_bantuan' => ['BLT', 'Sembako'],
            'tahun' => 2026,
            'status' => 'aktif',
            'keterangan' => 'Penerima bantuan reguler tahun 2026',
        ]);

        $warga2 = AnggotaKeluarga::with('kartuKeluarga')->skip(1)->first();
        if ($warga2) {
            PenerimaBantuan::create([
                'anggota_keluarga_id' => $warga2->id,
                'nik' => $warga2->nik,
                'no_kk' => $warga2->kartuKeluarga->no_kk ?? null,
                'jenis_bantuan' => ['PKH', 'BPNT'],
                'tahun' => 2026,
                'status' => 'aktif',
                'keterangan' => 'Program keluarga harapan',
            ]);
        }

        // Sample pengajuan kurang mampu
        $warga3 = AnggotaKeluarga::with('kartuKeluarga')->skip(2)->first();
        if ($warga3) {
            PengajuanKurangMampu::create([
                'anggota_keluarga_id' => $warga3->id,
                'nik' => $warga3->nik,
                'no_kk' => $warga3->kartuKeluarga->no_kk ?? null,
                'penghasilan_per_bulan' => 1500000,
                'pekerjaan' => 'Buruh Harian',
                'jumlah_tanggungan' => 4,
                'status_rumah' => 'Kontrak',
                'kondisi_rumah' => 'Sedang',
                'alasan_pengajuan' => 'Penghasilan tidak tetap, memiliki 4 orang tanggungan yang masih sekolah.',
                'status' => 'menunggu',
            ]);
        }
    }
}
