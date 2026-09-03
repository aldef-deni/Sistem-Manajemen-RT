#!/usr/bin/env bash
#
# Auto-deploy Sistem Manajemen RT
# Dijalankan DI SERVER (aaPanel/VPS), dipicu oleh GitHub Actions atau webhook.
#
#   bash deploy.sh                 -> deploy normal
#   BUILD_ASSETS=0 bash deploy.sh  -> lewati build Vite (kalau Node tidak ada di server)
#   SEED=1 bash deploy.sh          -> jalankan db:seed setelah migrate
#
set -euo pipefail

APP_DIR="${APP_DIR:-$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)}"
cd "$APP_DIR"

# Konfigurasi opsional per-server. Buat file deploy.conf di sisi server bila
# perlu menimpa nilai di bawah (file ini tidak ikut ke git).
if [ -f "$APP_DIR/deploy.conf" ]; then
    # shellcheck disable=SC1091
    source "$APP_DIR/deploy.conf"
fi

BRANCH="${BRANCH:-main}"
REMOTE="${REMOTE:-origin}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"
BUILD_ASSETS="${BUILD_ASSETS:-1}"
SEED="${SEED:-0}"
MAINTENANCE="${MAINTENANCE:-1}"
WEB_USER="${WEB_USER:-www}"          # aaPanel = www, cPanel = user cPanel, nginx murni = www-data
PHP_FPM_SERVICE="${PHP_FPM_SERVICE:-}"  # contoh: php-fpm-83 (aaPanel) — kosongkan untuk melewati

LOG_FILE="$APP_DIR/storage/logs/deploy.log"
mkdir -p "$(dirname "$LOG_FILE")"
exec > >(tee -a "$LOG_FILE") 2>&1

# Cegah dua deploy berjalan bersamaan (push beruntun).
if command -v flock >/dev/null 2>&1; then
    exec 200>"$APP_DIR/storage/deploy.lock"
    if ! flock -n 200; then
        echo "!! Deploy lain sedang berjalan. Dibatalkan."
        exit 1
    fi
fi

step()  { echo ""; echo "==> $*"; }
info()  { echo "    $*"; }

echo "================================================================"
echo " Deploy Sistem Manajemen RT — $(date '+%Y-%m-%d %H:%M:%S %Z')"
echo " Direktori : $APP_DIR"
echo " Branch    : $REMOTE/$BRANCH"
echo "================================================================"

if [ ! -f "$APP_DIR/artisan" ]; then
    echo "!! artisan tidak ditemukan. APP_DIR salah?"
    exit 1
fi
if [ ! -f "$APP_DIR/.env" ]; then
    echo "!! File .env tidak ada di server. Buat dulu sebelum deploy."
    exit 1
fi

OLD_REV="$(git rev-parse --short HEAD 2>/dev/null || echo '-')"

# --- Maintenance mode ---------------------------------------------------
BROUGHT_DOWN=0
bring_up() {
    if [ "$BROUGHT_DOWN" = "1" ]; then
        "$PHP_BIN" artisan up || true
        BROUGHT_DOWN=0
    fi
}
trap bring_up EXIT

if [ "$MAINTENANCE" = "1" ]; then
    step "Mengaktifkan maintenance mode"
    "$PHP_BIN" artisan down --retry=15 || true
    BROUGHT_DOWN=1
fi

# --- Ambil kode terbaru -------------------------------------------------
step "Menarik kode terbaru dari $REMOTE/$BRANCH"
git fetch "$REMOTE" "$BRANCH" --prune
# reset --hard menyamakan file yang DILACAK git dengan remote.
# File tak terlacak (.env, public/uploads, storage) TIDAK ikut terhapus.
git reset --hard "$REMOTE/$BRANCH"
NEW_REV="$(git rev-parse --short HEAD)"
info "$OLD_REV -> $NEW_REV"
git log -1 --pretty='    %h %s (%an, %ar)'

# --- Dependency PHP -----------------------------------------------------
step "composer install (production)"
"$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

# --- Build aset front-end ----------------------------------------------
if [ "$BUILD_ASSETS" = "1" ]; then
    if command -v "$NPM_BIN" >/dev/null 2>&1; then
        step "Build aset Vite"
        if [ -f package-lock.json ]; then
            "$NPM_BIN" ci --no-audit --no-fund
        else
            "$NPM_BIN" install --no-audit --no-fund
        fi
        "$NPM_BIN" run build
    else
        echo "!! npm tidak ditemukan di server — build aset dilewati."
        echo "   Install Node.js lewat aaPanel, atau set BUILD_ASSETS=0 dan"
        echo "   commit folder public/build dari lokal."
    fi
else
    info "BUILD_ASSETS=0, build aset dilewati."
fi

# --- Database -----------------------------------------------------------
step "Migrasi database"
"$PHP_BIN" artisan migrate --force

if [ "$SEED" = "1" ]; then
    step "Menjalankan seeder"
    "$PHP_BIN" artisan db:seed --force
fi

# --- Storage link -------------------------------------------------------
if [ ! -e "$APP_DIR/public/storage" ]; then
    step "Membuat symlink storage"
    "$PHP_BIN" artisan storage:link
fi

# --- Cache Laravel ------------------------------------------------------
step "Menyegarkan cache Laravel"
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan event:cache || true

# --- Queue & scheduler --------------------------------------------------
"$PHP_BIN" artisan queue:restart >/dev/null 2>&1 || true

# --- Hak akses ----------------------------------------------------------
step "Menyetel hak akses"
if id -u "$WEB_USER" >/dev/null 2>&1; then
    chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache public/uploads 2>/dev/null || true
else
    info "User '$WEB_USER' tidak ada, chown dilewati."
fi
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
[ -d public/uploads ] && chmod -R 775 public/uploads 2>/dev/null || true

# --- Selesai ------------------------------------------------------------
bring_up
trap - EXIT

if [ -n "$PHP_FPM_SERVICE" ]; then
    step "Reload $PHP_FPM_SERVICE"
    systemctl reload "$PHP_FPM_SERVICE" 2>/dev/null || service "$PHP_FPM_SERVICE" reload 2>/dev/null || info "Reload gagal, lewati."
fi

echo ""
echo "================================================================"
echo " Selesai. $OLD_REV -> $NEW_REV  ($(date '+%H:%M:%S'))"
echo "================================================================"
