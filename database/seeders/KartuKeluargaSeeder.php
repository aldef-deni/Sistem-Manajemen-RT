<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Database\Seeder;

class KartuKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'no_kk' => '3315010000000002',
                'rt'    => '001',
                'rw'    => '002',
                'alamat'=> 'Jl. Merdeka No. 10',
                'desa'  => 'Sukamaju',
                'kecamatan' => 'Menteng',
                'kabupaten' => 'Kota Semarang',
                'kode_pos'  => '58211',
                'anggota' => [
                    ['nik' => '33150100000000021', 'nama' => 'Fikri Putra',   'jk' => 'L', 'hp' => '081213575562', 'hub' => 'Kepala Keluarga', 'tl' => '1985-03-15', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000022', 'nama' => 'Siti Aminah',   'jk' => 'P', 'hp' => '081213575563', 'hub' => 'Istri', 'tl' => '1987-07-22', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000023', 'nama' => 'Rizky Pratama', 'jk' => 'L', 'hp' => null, 'hub' => 'Anak', 'tl' => '2010-05-10', 'kawin' => null],
                ],
            ],
            [
                'no_kk' => '3315010000000003',
                'rt'    => '001',
                'rw'    => '002',
                'alamat'=> 'Jl. Merdeka No. 15',
                'desa'  => 'Sukamaju',
                'kecamatan' => 'Menteng',
                'kabupaten' => 'Kota Semarang',
                'kode_pos'  => '58211',
                'anggota' => [
                    ['nik' => '33150100000000031', 'nama' => 'Fajar Nugroho',  'jk' => 'L', 'hp' => '087888226012', 'hub' => 'Kepala Keluarga', 'tl' => '1990-01-20', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000032', 'nama' => 'Dewi Sari',      'jk' => 'P', 'hp' => '087888226013', 'hub' => 'Istri', 'tl' => '1992-06-15', 'kawin' => 'Kawin'],
                ],
            ],
            [
                'no_kk' => '3315010000000004',
                'rt'    => '001',
                'rw'    => '002',
                'alamat'=> 'Jl. Pemuda No. 5',
                'desa'  => 'Sukamaju',
                'kecamatan' => 'Menteng',
                'kabupaten' => 'Kota Semarang',
                'kode_pos'  => '58211',
                'anggota' => [
                    ['nik' => '33150100000000041', 'nama' => 'Jaka Hakim',     'jk' => 'L', 'hp' => '088122429110', 'hub' => 'Kepala Keluarga', 'tl' => '1980-11-05', 'kawin' => 'Kawin'],
                ],
            ],
            [
                'no_kk' => '3315010000000005',
                'rt'    => '002',
                'rw'    => '002',
                'alamat'=> 'Jl. Sudirman No. 22',
                'desa'  => 'Sukamaju',
                'kecamatan' => 'Menteng',
                'kabupaten' => 'Kota Semarang',
                'kode_pos'  => '58211',
                'anggota' => [
                    ['nik' => '33150100000000051', 'nama' => 'Lukman Purnomo', 'jk' => 'L', 'hp' => '085247164955', 'hub' => 'Kepala Keluarga', 'tl' => '1982-09-20', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000052', 'nama' => 'Rina Wati',      'jk' => 'P', 'hp' => null, 'hub' => 'Istri', 'tl' => '1985-04-12', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000053', 'nama' => 'Andi Saputra',   'jk' => 'L', 'hp' => null, 'hub' => 'Anak', 'tl' => '2008-03-08', 'kawin' => null],
                    ['nik' => '33150100000000054', 'nama' => 'Maya Putri',     'jk' => 'P', 'hp' => null, 'hub' => 'Anak', 'tl' => '2012-07-25', 'kawin' => null],
                ],
            ],
            [
                'no_kk' => '3315010000000006',
                'rt'    => '002',
                'rw'    => '002',
                'alamat'=> 'Jl. Gatot Subroto No. 8',
                'desa'  => 'Sukamaju',
                'kecamatan' => 'Menteng',
                'kabupaten' => 'Kota Semarang',
                'kode_pos'  => '58211',
                'anggota' => [
                    ['nik' => '33150100000000061', 'nama' => 'Ivan Perdana',   'jk' => 'L', 'hp' => '088285374605', 'hub' => 'Kepala Keluarga', 'tl' => '1975-12-01', 'kawin' => 'Kawin'],
                    ['nik' => '33150100000000062', 'nama' => 'Lestari Wulan',  'jk' => 'P', 'hp' => null, 'hub' => 'Istri', 'tl' => '1978-08-18', 'kawin' => 'Kawin'],
                ],
            ],
        ];

        foreach ($data as $d) {
            $kk = KartuKeluarga::create([
                'no_kk'     => $d['no_kk'],
                'rt'        => $d['rt'],
                'rw'        => $d['rw'],
                'alamat'    => $d['alamat'],
                'desa'      => $d['desa'],
                'kecamatan' => $d['kecamatan'],
                'kabupaten' => $d['kabupaten'],
                'kode_pos'  => $d['kode_pos'],
            ]);

            foreach ($d['anggota'] as $a) {
                $kk->anggota()->create([
                    'nik'             => $a['nik'],
                    'nama_lengkap'    => $a['nama'],
                    'jenis_kelamin'   => $a['jk'],
                    'no_hp'           => $a['hp'],
                    'tanggal_lahir'   => $a['tl'] ?? null,
                    'status_hubungan' => $a['hub'],
                    'status_kawin'    => $a['kawin'] ?? null,
                    'domisili'        => 'Tetap',
                    'role'            => 'Warga',
                ]);
            }
        }
    }
}
