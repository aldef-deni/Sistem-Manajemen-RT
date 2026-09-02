<?php

namespace Database\Seeders;

use App\Models\StrukturRT;
use App\Models\PengurusRT;
use App\Models\SettingRT;
use Illuminate\Database\Seeder;

class StrukturRTSeeder extends Seeder
{
    public function run(): void
    {
        $struktur = StrukturRT::create([
            'nama_rt' => 'RT 005',
            'nomor_rt' => '005',
            'nomor_rw' => '003',
            'alamat_rt' => 'Jl. Merdeka No. 10, RT 005/RW 003',
            'kelurahan' => 'Sukamaju',
            'kecamatan' => 'Cilandak',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'telepon_rt' => '(021) 7654-3210',
            'email_rt' => 'rt005@sukamaju.id',
            'visi' => 'Mewujudkan lingkungan RT 005 yang aman, nyaman, gotong royong, dan sejahtera bagi seluruh warga.',
            'misi' => '1. Meningkatkan keamanan dan ketertiban lingkungan\n2. Mengoptimalkan pelayanan administrasi warga\n3. Mengembangkan kegiatan sosial dan keagamaan\n4. Menjaga kebersihan dan keasrian lingkungan\n5. Meningkatkan kesejahteraan warga melalui program ekonomi',
            'peraturan' => "PERATURAN TATA TERTIB LINGKUNGAN\nRT 005 / RW 003\nKelurahan Sukamaju, Kecamatan Cilandak, Kota Jakarta Selatan\n\nI. PENDAHULUAN\n1. Peraturan ini dibuat berdasarkan musyawarah warga RT 005/RW 003 pada tanggal 1 Januari 2026.\n2. Tujuannya adalah menciptakan ketertiban, keamanan, dan kenyamanan bersama.\n\nII. DEFINISI\n1. Warga adalah setiap orang yang berdomisili di wilayah RT 005/RW 003.\n2. Lingkungan adalah seluruh wilayah RT 005/RW 003 beserta fasilitas umum.\n\nIII. PERATURAN UMUM\n1. Warga wajib menjaga ketertiban dan keamanan lingkungan.\n2. Warga wajib membayar iuran warga tepat waktu.\n3. Warga wajib menjaga kebersihan lingkungan masing-masing.\n4. Setiap warga wajib menghormati hak tetangga lainnya.\n\nIV. PERATURAN KETERTIBAN\n1. Dilarang membuat kegaduhan di atas pukul 22.00 WIB.\n2. Dilarang membuang sampah sembarangan.\n3. Dilarang parkir kendaraan di jalan umum.\n4. Tamu yang menginap wajib dilaporkan ke RT.\n\nV. PERATURAN KEAMANAN\n1. Warga wajib berpartisipasi dalam ronda malam.\n2. Setiap rumah wajib memiliki lampu penerangan jalan.\n3. Warga wajib melaporkan hal mencurigakan ke pengurus RT.\n4. Kendaraan wajib diparkir di tempat yang telah disediakan.\n\nVI. PERATURAN KEBERSIHAN\n1. Warga wajib menjaga kebersihan depan rumah masing-masing.\n2. Sampah harus dibuang pada jadwal yang telah ditentukan.\n3. Selokan di depan rumah wajib dibersihkan secara berkala.\n4. Gotong royong dilaksanakan setiap minggu pertama.\n\nVII. PERATURAN PEMBAYARAN\n1. Iuran warga dibayarkan setiap bulan paling lambat tanggal 15.\n2. Keterlambatan pembayaran dikenakan denda sesuai ketentuan.\n3. Pembayaran dapat dilakukan secara tunai atau transfer.\n\nVIII. SANKSI / DENDA\n1. Pelanggaran ringan: teguran lisan\n2. Pelanggaran sedang: teguran tertulis\n3. Pelanggaran berat: musyawarah warga\n4. Denda keterlambatan iuran: Rp 10.000/bulan\n\nIX. PENUTUP\n1. Peraturan ini berlaku sejak ditetapkan.\n2. Perubahan dapat dilakukan melalui musyawarah warga.\n3. Atas perhatian dan kepatuhan warga, kami ucapkan terima kasih.",
        ]);

        // Pengurus Inti
        $pengurus = [
            ['nama' => 'Suharto', 'jabatan' => 'Ketua RT', 'telepon' => '081234567890', 'email' => 'suharto@email.com', 'urutan' => 1, 'keterangan' => 'Ketua RT periode 2024-2026'],
            ['nama' => 'Rachmat', 'jabatan' => 'Wakil Ketua RT', 'telepon' => '081234567891', 'email' => 'rachmat@email.com', 'urutan' => 2, 'keterangan' => 'Wakil Ketua RT'],
            ['nama' => 'Wulandari', 'jabatan' => 'Sekretaris', 'telepon' => '081234567892', 'email' => 'wulandari@email.com', 'urutan' => 3, 'keterangan' => 'Sekretaris RT'],
            ['nama' => 'Ratna', 'jabatan' => 'Bendahara', 'telepon' => '081234567893', 'email' => 'ratna@email.com', 'urutan' => 4, 'keterangan' => 'Bendahara RT'],
            ['nama' => 'Hendra', 'jabatan' => 'Ketua Keamanan', 'telepon' => '081234567894', 'email' => 'hendra@email.com', 'urutan' => 5, 'keterangan' => 'Koordinator keamanan lingkungan'],
            ['nama' => 'Sari', 'jabatan' => 'Ketua Kebersihan', 'telepon' => '081234567895', 'email' => 'sari@email.com', 'urutan' => 6, 'keterangan' => 'Koordinator kebersihan lingkungan'],
            ['nama' => 'Darmawan', 'jabatan' => 'Sie. Perlengkapan', 'telepon' => '081234567896', 'email' => 'darmawan@email.com', 'urutan' => 7, 'keterangan' => 'Pengadaan perlengkapan RT'],
            ['nama' => 'Kartini', 'jabatan' => 'Sie. Dokumentasi', 'telepon' => '081234567897', 'email' => 'kartini@email.com', 'urutan' => 8, 'keterangan' => 'Dokumentasi kegiatan RT'],
            ['nama' => 'Budi', 'jabatan' => 'Koordinator Blok A', 'telepon' => '081234567898', 'email' => 'budi@email.com', 'urutan' => 9, 'keterangan' => 'Koordinator warga Blok A'],
            ['nama' => 'Ani', 'jabatan' => 'Koordinator Blok B', 'telepon' => '081234567899', 'email' => 'ani@email.com', 'urutan' => 10, 'keterangan' => 'Koordinator warga Blok B'],
        ];

        foreach ($pengurus as $p) {
            PengurusRT::create(array_merge($p, ['struktur_rt_id' => $struktur->id, 'status' => 'aktif']));
        }

        // Settings
        SettingRT::set('grup_wa_link', 'https://chat.whatsapp.com/ContohLinkRT005', 'Link Grup WhatsApp RT 005');
        SettingRT::set('youtube_channel', '', 'Channel YouTube');
        SettingRT::set('rumah_ibadah', 'Masjid Al-Ikhlas, Musholla An-Nur', 'Rumah Ibadah di lingkungan RT');
    }
}
