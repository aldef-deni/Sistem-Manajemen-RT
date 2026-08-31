<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surat;
use App\Models\AnggotaKeluarga;
use App\Models\User;
use Carbon\Carbon;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'Administrator')->first();
        $warga = AnggotaKeluarga::first();

        if (!$warga) return;

        $suratList = [
            [
                'kode_surat' => 'SR-260801-0001',
                'anggota_keluarga_id' => $warga->id,
                'nama_pemohon' => $warga->nama_lengkap,
                'nik' => $warga->nik,
                'jenis_surat' => 'Surat Keterangan Domisili',
                'keperluan' => 'Untuk keperluan pendaftaran sekolah anak di SDN 01 Metro',
                'status' => 'selesai',
                'nomor_surat' => '470/SM-RT/VIII/2026',
                'tanggal_proses' => Carbon::now()->subDays(3),
                'tanggal_selesai' => Carbon::now()->subDays(1),
                'catatan_admin' => 'Surat sudah dicetak dan diberikan kepada pemohon',
                'diproses_oleh' => $admin?->id,
            ],
            [
                'kode_surat' => 'SR-260830-0001',
                'anggota_keluarga_id' => $warga->id,
                'nama_pemohon' => $warga->nama_lengkap,
                'nik' => $warga->nik,
                'jenis_surat' => 'Surat Keterangan Usaha',
                'keperluan' => 'Untuk pengajuan pinjaman modal usaha di Bank BRI',
                'status' => 'diproses',
                'tanggal_proses' => now()->toDateString(),
                'catatan_admin' => 'Sedang dalam proses verifikasi data usaha',
                'diproses_oleh' => $admin?->id,
            ],
            [
                'kode_surat' => 'SR-260830-0002',
                'anggota_keluarga_id' => $warga->id,
                'nama_pemohon' => $warga->nama_lengkap,
                'nik' => $warga->nik,
                'jenis_surat' => 'Surat Keterangan Tidak Mampu',
                'keperluan' => 'Untuk pengajuan bantuan sosial dari dinas sosial',
                'status' => 'pending',
            ],
        ];

        foreach ($suratList as $surat) {
            Surat::create($surat);
        }
    }
}
