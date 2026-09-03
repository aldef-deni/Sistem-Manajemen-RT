# Auto Deploy — Sistem Manajemen RT

Setiap `git push` ke branch **`main`** akan otomatis men-deploy ke
**https://rt.aldeftech.com**.

```
push ke main  ->  GitHub Actions  ->  npm run build (aset Vite)
                                  ->  kirim public/build ke server (scp)
                                  --ssh-->  bash deploy.sh di server
```

Aset front-end dibangun **di GitHub**, bukan di VPS — jadi server tidak perlu
Node.js dan tidak kehabisan RAM saat build.

File yang terlibat:

| File | Jalan di mana | Fungsi |
|---|---|---|
| `.github/workflows/deploy.yml` | GitHub | Terpicu saat push, SSH ke server |
| `deploy.sh` | Server | Pull, composer, migrate, cache, hak akses |
| `deploy.conf` | Server (opsional, tidak di git) | Menimpa setelan per-server |

---

## 1. Siapkan aplikasi di server (sekali saja)

SSH ke VPS, lalu clone repo ke folder situs aaPanel:

```bash
cd /www/wwwroot
rm -rf rt.aldeftech.com                # kosongkan folder bawaan aaPanel
git clone https://github.com/aldef-deni/Sistem-Manajemen-RT.git rt.aldeftech.com
cd rt.aldeftech.com

cp .env.example .env
nano .env                              # isi APP_URL, DB, dll (lihat bagian 4)

composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force            # data awal + akun pengurus
php artisan storage:link

chown -R www:www storage bootstrap/cache public/uploads
chmod -R 775 storage bootstrap/cache public/uploads
```

Di aaPanel, set **Document Root** situs ke `/www/wwwroot/rt.aldeftech.com/public`
(bukan folder root-nya) — ini wajib untuk Laravel.

Kalau repo-nya privat, gunakan URL SSH (`git@github.com:...`) dan daftarkan
public key server sebagai **Deploy Key** di GitHub → repo → Settings → Deploy keys.

---

## 2. Buat SSH key untuk GitHub Actions

Jangan pakai kunci pribadi Anda — buat kunci khusus deploy. **Di server:**

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/gh_deploy -N ""
cat ~/.ssh/gh_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

cat ~/.ssh/gh_deploy        # <- ini PRIVATE key, salin SELURUHNYA
```

Salin mulai dari `-----BEGIN OPENSSH PRIVATE KEY-----`
sampai `-----END OPENSSH PRIVATE KEY-----` (termasuk kedua baris itu).

---

## 3. Isi Secrets di GitHub

Repo → **Settings → Secrets and variables → Actions → New repository secret**.
Buat 5 secret berikut:

| Nama secret | Isi | Contoh |
|---|---|---|
| `SSH_HOST` | IP / hostname VPS | `34.101.155.148` |
| `SSH_PORT` | Port SSH | `22` (aaPanel sering mengubahnya, cek Security) |
| `SSH_USER` | User SSH | `root` |
| `SSH_KEY` | Isi file `~/.ssh/gh_deploy` (private key) | `-----BEGIN OPENSSH...` |
| `APP_DIR` | Path aplikasi di server | `/www/wwwroot/rt.aldeftech.com` |

> Kalau firewall aaPanel membatasi IP yang boleh SSH, runner GitHub tidak akan
> bisa masuk (IP-nya berubah-ubah). Dalam kasus itu, pakai **cara alternatif
> webhook** di bagian 6.

---

## 4. `.env` di server

`.env` **tidak** ikut di-push (sudah di `.gitignore`) dan tidak akan tertimpa
oleh deploy. Isinya minimal:

```env
APP_NAME="Sistem Manajemen RT"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rt.aldeftech.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=sandi_database

SESSION_DRIVER=database
CACHE_STORE=database
```

`APP_DEBUG=false` itu penting — kalau `true`, pesan error beserta isi `.env`
bisa terlihat pengunjung.

> Lokal masih memakai `sqlite`. Di server sebaiknya MySQL; buat database-nya
> lewat aaPanel → Databases.

---

## 5. Coba jalankan

```bash
git add .
git commit -m "setup auto deploy"
git push origin main
```

Pantau di GitHub → tab **Actions**. Di server, log lengkapnya tersimpan di:

```bash
tail -f /www/wwwroot/rt.aldeftech.com/storage/logs/deploy.log
```

Deploy manual tanpa push: GitHub → Actions → *Deploy ke rt.aldeftech.com* →
**Run workflow**. Atau langsung di server: `BUILD_ASSETS=0 bash deploy.sh`
(pakai aset yang sudah ada; untuk membangun ulang aset, jalankan lewat Actions).

---

## 6. Alternatif: webhook aaPanel (kalau SSH dari luar diblokir)

1. aaPanel → **App Store** → pasang plugin **Webhook**.
2. Tambah hook baru, isi script-nya:

   ```bash
   #!/bin/bash
   cd /www/wwwroot/rt.aldeftech.com && bash deploy.sh
   ```

3. Salin URL webhook yang muncul (`http://IP:PORT/hook?access_key=...`).
4. GitHub → repo → Settings → **Webhooks → Add webhook**:
   - Payload URL: URL dari langkah 3
   - Content type: `application/json`
   - Event: *Just the push event*

Kalau memakai cara ini, file `.github/workflows/deploy.yml` boleh dihapus.

---

## 7. Setelan tambahan (`deploy.conf` di server)

Buat file `deploy.conf` di folder aplikasi **di server** bila perlu:

```bash
PHP_BIN=/www/server/php/83/bin/php   # sesuaikan versi, cek: ls /www/server/php/
BUILD_ASSETS=0          # default dari workflow: aset sudah dibangun di GitHub
MAINTENANCE=0           # jangan aktifkan halaman maintenance saat deploy
SEED=0                  # 1 = jalankan db:seed tiap deploy (biasanya jangan)
CHOWN_TO=""             # isi "aldeftech:www" HANYA bila deploy dijalankan sebagai root
PHP_FPM_SERVICE=""      # isi php-fpm-83 HANYA bila deploy dijalankan sebagai root
```

Cari path PHP yang benar dengan: `ls /www/server/php/`

---

## Yang perlu diperhatikan

**Aset Vite tidak ada di repo.** `public/build/` di-gitignore dan dikirim ke
server oleh GitHub Actions setiap deploy. Kalau Anda pernah `git clone` manual
lalu membuka situs dan muncul *"Vite manifest not found"*, itu wajar — jalankan
sekali deploy lewat Actions (atau **Run workflow**) dan aset akan terkirim.



**`git reset --hard` di `deploy.sh`.** Deploy menyamakan file yang dilacak git
dengan isi `main`. Artinya: **jangan pernah mengedit file langsung di server** —
perubahan itu akan hilang di deploy berikutnya. Semua perubahan lewat commit.

File yang *tidak* dilacak git aman dan tidak tersentuh: `.env`, `storage/`,
`public/uploads/` (foto profil, KK, bukti pengaduan), `vendor/`, `node_modules/`.

**Folder unggahan sudah dikeluarkan dari repo.** `public/uploads/` kini hanya
menyimpan struktur foldernya (lewat berkas `.gitkeep`); isinya — KK, bukti
pengaduan, foto pengurus dan profil — milik masing-masing server dan tidak
pernah ikut push maupun tertimpa deploy.

> Kalau server sudah pernah di-clone sebelum perubahan ini, deploy pertama akan
> menghapus `public/uploads/profil/foto_1_1788404641.png` di server (foto profil
> contoh yang dulu ikut ter-commit). Backup dulu bila masih dipakai:
> `cp -r public/uploads /root/uploads-backup`

**Migrasi bersifat maju saja.** `deploy.sh` menjalankan `migrate --force`, tidak
pernah `migrate:fresh`. Data di server tidak akan terhapus oleh deploy.

**Butuh rollback?** Di server:

```bash
cd /www/wwwroot/rt.aldeftech.com
git log --oneline -10
git reset --hard <commit-lama>
php artisan optimize:clear && php artisan config:cache && php artisan route:cache
```

Rollback kode tidak membatalkan migrasi database — kalau commit itu menambah
tabel/kolom, jalankan `php artisan migrate:rollback` secara terpisah.
