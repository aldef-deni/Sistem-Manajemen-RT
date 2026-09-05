# Deploy Sistem Manajemen RT ke rt.aldeftech.com

Server: VM GCP **rumahchiara** (`aldef-tech`, zona `asia-southeast2-b`, IP `34.50.78.9`),
Ubuntu, dikelola **aaPanel** — VM yang sama dengan corearsitek.aldeftech.com.

Deploy dilakukan **lewat SSH langsung**, bukan GitHub Actions. Alurnya:
commit → push ke `main` → SSH ke server → `bash deploy.sh`.

| Komponen | Lokasi / versi |
|---|---|
| Folder aplikasi | `/www/wwwroot/rt.aldeftech.com` |
| Running directory (aaPanel) | `/www/wwwroot/rt.aldeftech.com/public` |
| Repo | https://github.com/aldef-deni/Sistem-Manajemen-RT (branch `main`) |
| PHP | 8.4 — `/www/server/php/84/bin/php` |
| Composer | `/home/aldeftech/bin/composer` |
| Node / npm | v22 / v9 (`/usr/bin/node`) — aset Vite dibangun di server |
| Database | MySQL 8.0, `sql_rt_aldeftech_com` |
| User pemilik berkas | `aldeftech:www` |
| User SSH | `aldeftech` (tanpa sudo) |
| User root | `ubuntu` lewat terminal aaPanel, atau `sudo` dari sana |

## Masuk ke server

```bash
gcloud compute ssh rumahchiara --zone=asia-southeast2-b
```

## Deploy rutin

```bash
cd /www/wwwroot/rt.aldeftech.com && bash deploy.sh
```

Atau sekali jalan dari mesin lokal:

```bash
gcloud compute ssh rumahchiara --zone=asia-southeast2-b \
  --command="cd /www/wwwroot/rt.aldeftech.com && bash deploy.sh"
```

Skrip menarik `main`, memasang dependensi composer, membangun aset Vite,
menjalankan migrasi, menyegarkan cache, dan memperbaiki hak akses.
`.env` dan `public/uploads/` tidak pernah disentuh. Log tersimpan di
`storage/logs/deploy.log`, dan ada file lock supaya dua deploy tidak bertabrakan.

## Setelan per-server: `deploy.conf`

Ada di server, tidak ikut git:

```
PHP_BIN=/www/server/php/84/bin/php
COMPOSER_BIN=/home/aldeftech/bin/composer
BUILD_ASSETS=1
MAINTENANCE=1
```

`BUILD_ASSETS=1` karena VM ini punya Node. Kalau suatu saat Node hilang, ubah ke
`0` lalu bangun aset di lokal dan kirim manual:

```bash
npm run build
gcloud compute scp --recurse --zone=asia-southeast2-b public/build \
  rumahchiara:/www/wwwroot/rt.aldeftech.com/public/
```

## Hak akses berkas

Seluruh folder aplikasi milik `aldeftech:www`, dan **semua direktori diberi bit
setgid** supaya berkas baru — hasil `git pull`, composer, atau unggahan warga —
otomatis mewarisi grup `www`. Tanpa itu php-fpm (yang jalan sebagai `www`) tidak
bisa menulis ke `storage/` dan situs balas HTTP 500 tanpa meninggalkan log.

Kalau kepemilikan pernah kacau lagi, perbaiki dari **terminal aaPanel sebagai
`ubuntu`** (user `aldeftech` tidak punya sudo):

```bash
sudo chown -R aldeftech:www /www/wwwroot/rt.aldeftech.com
sudo chmod -R g+rwX /www/wwwroot/rt.aldeftech.com/{storage,bootstrap/cache,public/uploads}
sudo find /www/wwwroot/rt.aldeftech.com -type d -exec chmod g+s {} +
```

> **Jangan** menekan tombol *Set directory permissions* di aaPanel File Manager
> untuk situs ini — kepemilikan kembali ke `www:www` dan `git pull` langsung gagal.
> `public/.user.ini` diproteksi immutable oleh panel, jadi `chown -R` selalu
> gagal di berkas itu. Abaikan, memang tidak perlu diubah.

## Pemasangan awal (sudah dikerjakan, disimpan sebagai rujukan)

1. Repo di-clone ke folder situs bawaan aaPanel; `index.html`, `404.html`,
   `502.html`, `.well-known/`, dan `.user.ini` milik panel dibiarkan.
2. `.env` ditulis manual (bukan dari `.env.example`) dengan `APP_ENV=production`,
   `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, dan kredensial MySQL.
3. `composer install --no-dev`, `key:generate`, `migrate --force`,
   `db:seed --force`, `storage:link`.
4. aaPanel → Website → Settings: **Running directory `/public`**,
   **URL rewrite preset `laravel5`**, **PHP 8.4**.
5. `php-cli.ini` punya baris `extension = zip.so` ganda yang memunculkan
   `PHP Warning: Module "zip" is already loaded` di setiap perintah artisan —
   duplikatnya sudah di-comment. Perhatikan CLI membaca `php-cli.ini`,
   sedangkan FPM membaca `php.ini`; keduanya berkas terpisah.

## Pengujian

```bash
php artisan test
```

29 uji, jalan di SQLite in-memory sehingga tidak menyentuh database mana pun.

| Berkas | Yang dijaga |
|---|---|
| `tests/Feature/SmokeRouteTest.php` | Setiap rute GET dibuka sebagai empat peran; tidak boleh ada 5xx |
| `tests/Feature/HakAksesTest.php` | Warga ditolak dari halaman pengurus, Ketua tidak bisa mengambil alih akun Administrator, lampiran `.php` ditolak |
| `tests/Feature/CrudModulTest.php` | Buat → baca → ubah → hapus pada 19 modul, termasuk saldo kas dan tabungan benar-benar berubah |

**Jalankan sebelum setiap deploy.** Tiga bug ditemukan justru oleh uji ini dan
bukan oleh pembacaan kode: fungsi global di dalam `@php` yang fatal saat view
dirender dua kali, kolom `nullable` Kartu Keluarga yang dibaca tanpa `??`, dan
kegagalan halaman detail Arisan.

Uji berjalan di SQLite sedangkan produksi memakai MySQL. Karena itu tidak ada
lagi SQL khusus satu dialek di dalam kode — lihat `AnggotaKeluarga::usiaAntara()`.

## Hak akses peran

| Kelompok | Peran | Cakupan |
|---|---|---|
| tanpa `role:` | semua yang sudah masuk | dashboard, profil, pengumuman, kalender, struktur, polling, pengaduan, dan pengajuan mandiri (UMKM, bantuan sosial, pinjaman) |
| `role:admin,ketua,pengurus` | pengurus ke atas | kependudukan, keuangan, inventaris, layanan, dokumentasi |
| `role:admin,ketua` | admin & ketua | pengaturan, kelola pengurus, kelola akun |

Menu sidebar disaring oleh `App\Support\MenuAccess`, yang membaca middleware
`role:` pada route bersangkutan — jadi menu tidak bisa melenceng dari aturan
akses yang sesungguhnya.

## Berkas unggahan

`App\Support\SafeUpload` membuat sendiri nama berkas dan hanya menerima
ekstensi dari daftar yang diizinkan; ekstensi dari pengunggah tidak pernah
dipakai apa adanya. Lapis keduanya di nginx:

```
/www/server/panel/vhost/nginx/extension/rt.aldeftech.com/uploads-no-exec.conf
```

Berkas itu memblokir eksekusi `.php`, `.phtml`, `.phar`, `.cgi`, `.pl`, `.py`,
dan `.sh` di seluruh `/uploads`. Diletakkan di folder `extension` karena
aaPanel meng-include-nya lebih awal daripada `enable-php-84.conf`, sehingga
aturannya menang. Setelah mengubahnya jalankan `nginx -t` lalu `nginx -s reload`,
dan beri jeda sebentar — reload nginx bersifat graceful, worker lama masih
melayani beberapa saat.

## Seeder

`db:seed` dijalankan **sekali saja** saat pemasangan. Jangan diulang di server:
`AdminSeeder` memakai `updateOrCreate`, sehingga password akun yang sudah diganti
akan dikembalikan ke nilai bawaan. `deploy.sh` sengaja tidak memanggil seeder.

Akun bawaan: `admin` / `ketua` / `pengurus` / `warga`, password awal `password`.
**Ganti segera** — repo ini publik dan seeder-nya terbaca siapa saja:

```bash
php artisan tinker --execute="\App\Models\User::where('username','admin')->update(['password' => bcrypt('sandi-baru')]);"
```

Login menerima username, email, atau nama lengkap ([LoginController.php:29-31](app/Http/Controllers/Auth/LoginController.php#L29-L31)).

## Yang perlu diperhatikan

**`deploy.sh` menjalankan `git reset --hard origin/main`.** Jangan pernah mengedit
berkas langsung di server — perubahannya hilang di deploy berikutnya. Berkas tak
terlacak aman: `.env`, `deploy.conf`, `storage/`, `public/uploads/`, `vendor/`,
`node_modules/`, dan berkas bawaan aaPanel.

**Aset Vite tidak ada di repo.** `public/build/` di-gitignore dan dibangun ulang
di server tiap deploy. Kalau situs tampil tanpa CSS atau melempar
*"Vite manifest not found"*, jalankan `bash deploy.sh` sekali lagi.

**Migrasi bersifat maju saja.** `deploy.sh` hanya `migrate --force`, tidak pernah
`migrate:fresh`. Data di server tidak akan terhapus oleh deploy.

**Rollback:**

```bash
cd /www/wwwroot/rt.aldeftech.com
git log --oneline -10
git reset --hard <commit-lama>
php artisan optimize:clear && php artisan config:cache && php artisan route:cache
```

Rollback kode tidak membatalkan migrasi. Kalau commit itu menambah tabel/kolom,
jalankan `php artisan migrate:rollback` terpisah.

## Timezone

Aplikasi memakai **WIB (`Asia/Jakarta`)** — [config/app.php:70](config/app.php#L70)
membacanya dari `APP_TIMEZONE` dengan bawaan `Asia/Jakarta`. Untuk memakai WITA
atau WIT, isi `APP_TIMEZONE=Asia/Makassar` atau `Asia/Jayapura` di `.env` lalu
jalankan `php artisan config:cache`.

Laravel menyimpan stempel waktu ke database dalam timezone aplikasi, bukan UTC.
Baris yang ditulis sebelum 2026-09-05 dibuat saat aplikasi masih UTC, jadi
tampil 7 jam lebih awal dari waktu sebenarnya. Isinya hanya data contoh dari
seeder, jadi dibiarkan apa adanya; data yang dibuat setelah itu sudah benar WIB.
