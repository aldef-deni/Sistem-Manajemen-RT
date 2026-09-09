<p align="center">
  <a href="https://rt.aldeftech.com" target="_blank">
    <img src="public/images/aldef-landscape.png" width="520" alt="Logo Aldef Tech">
  </a>
</p>

<h1 align="center">Sistem Manajemen RT</h1>

<p align="center">
  <strong>Kelola administrasi, layanan, dan kegiatan warga dalam satu sistem.</strong>
</p>

<p align="center">
  <a href="https://rt.aldeftech.com"><img src="https://img.shields.io/badge/Demo-rt.aldeftech.com-0ea5e9?style=flat-square" alt="Demo Sistem Manajemen RT"></a>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2 atau lebih baru">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
</p>

## Tentang Sistem Manajemen RT

**Sistem Manajemen RT** adalah aplikasi berbasis web untuk membantu pengurus RT mengelola administrasi lingkungan secara terpusat, tertib, dan transparan. Aplikasi ini menghubungkan kebutuhan pengurus dan warga dalam satu platform, mulai dari pengelolaan data kependudukan dan keuangan hingga penyampaian informasi serta layanan mandiri warga.

Sistem dirancang dengan pembagian hak akses berdasarkan peran agar setiap pengguna memperoleh menu dan kewenangan yang sesuai. Selain antarmuka web, tersedia REST API berbasis token untuk mendukung pengembangan aplikasi mobile.

## Fitur Utama

- **Administrasi kependudukan** — data warga, kartu keluarga, anggota keluarga, serta daftar pemilih pemilu.
- **Keuangan RT** — pengelolaan iuran warga, kas RT, tabungan, pinjaman, dan arisan.
- **Inventaris** — pencatatan barang, rencana pembelian, serta peminjaman dan pengembalian barang.
- **Layanan warga** — pengaduan, surat-menyurat, pengajuan bantuan sosial, pinjaman, dan pendaftaran UMKM.
- **Informasi dan kegiatan** — pengumuman, kalender, jadwal kegiatan, dokumentasi kegiatan, serta notulen rapat.
- **Partisipasi warga** — polling dan pemungutan suara dengan pembatasan satu suara per pengguna.
- **Tata kelola organisasi** — profil RT, struktur kepengurusan, tata tertib, dan manajemen akun.
- **E-Visitor** — pencatatan kunjungan tamu beserta waktu masuk dan keluar.
- **API aplikasi mobile** — autentikasi Laravel Sanctum, informasi warga, layanan mandiri, dan fungsi pengelolaan sesuai peran.

## Hak Akses

| Peran | Cakupan |
| --- | --- |
| Warga | Dashboard, profil, informasi, polling, pengaduan, dan pengajuan layanan mandiri |
| Pengurus | Seluruh akses warga ditambah pengelolaan kependudukan, keuangan, inventaris, layanan, dan dokumentasi |
| Ketua RT | Seluruh akses pengurus ditambah pengaturan organisasi dan pengelolaan akun |
| Administrator | Akses penuh, termasuk menentukan dan mengubah peran pengguna |

Hak akses diterapkan pada route web dan API. Menu aplikasi juga disaring agar selalu mengikuti kewenangan route yang sebenarnya.

## Teknologi

| Area | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Tailwind CSS 4, JavaScript |
| Database | MySQL |
| Autentikasi API | Laravel Sanctum 4 |
| Asset bundler | Vite 7 |
| Testing | PHPUnit 11 |

## Menjalankan Aplikasi

Pastikan PHP 8.2+, Composer, Node.js, npm, dan database MySQL telah tersedia.

```bash
git clone https://github.com/aldef-deni/Sistem-Manajemen-RT.git
cd Sistem-Manajemen-RT
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Atur koneksi MySQL melalui variabel `DB_*` di dalam `.env`, kemudian siapkan database dan aset frontend:

```bash
php artisan migrate
npm run build
php artisan serve
```

Untuk menjalankan server aplikasi, queue listener, log viewer, dan Vite secara bersamaan:

```bash
composer dev
```

Jalankan pengujian dengan:

```bash
composer test
```

## Kustomisasi

<p align="center">
  <strong>JIKA BERMINAT UNTUK KUSTOMISASI BISA MENGHUBUNGI DENI AFRIZAL</strong>
</p>

<p align="center">
  <a href="https://wa.me/628128968609" target="_blank">
    <img src="https://img.shields.io/badge/WhatsApp-Hubungi_Deni_Afrizal-25D366?style=for-the-badge&logo=whatsapp&logoColor=white" alt="Hubungi Deni Afrizal melalui WhatsApp">
  </a>
</p>

## Kontak

Punya kebutuhan sistem administrasi lingkungan, aplikasi bisnis, integrasi, atau pengembangan fitur khusus? Kunjungi [aldeftech.com/contact](https://aldeftech.com/contact) untuk mendiskusikan kebutuhan Anda bersama Aldef Tech.

---

<p align="center">
  Dikembangkan oleh <a href="https://aldeftech.com">Aldef Tech</a><br>
  © Aldef Tech. Seluruh hak cipta dilindungi.
</p>
