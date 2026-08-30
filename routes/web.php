<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\IuranWargaController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\PemilihPemiluController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Login)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Warga
    Route::get('/data-warga', [DataWargaController::class, 'index'])->name('data-warga');

    // Pemilih Pemilu
    Route::get('/pemilih-pemilu', [PemilihPemiluController::class, 'index'])->name('pemilih-pemilu');

    // Kartu Keluarga CRUD
    Route::resource('kartu-keluarga', KartuKeluargaController::class);

    // Iuran Warga CRUD
    Route::resource('iuran-warga', IuranWargaController::class)->except(['show']);
    Route::patch('iuran-warga/{iuran_warga}/bayar', [IuranWargaController::class, 'bayar'])->name('iuran-warga.bayar');
    Route::post('iuran-warga/bayar-massal', [IuranWargaController::class, 'bayarMassal'])->name('iuran-warga.bayar-massal');

    // All Menu Pages
    $pages = [
        // Kependudukan
        'penduduk-pindah',
        'kas-rt',
        'pembayaran',
        'laporan-keuangan',
        'arisan-rt',

        // Inventaris
        'data-barang',

        // Dokumentasi
        'layanan-warga',
        'layanan',
        'surat-menunggu',
        'e-ktp',
        'buat-pengajuan',

        // Pengaturan & Info
        'pengaturan',
        'kalender',
        'jadwal-keamanan',

        // Keamanan & Kebersihan
        'patroli-rt',
        'ronda-rapat',
        'struktur-rt',

        // Media
        'video',
        'berita',

        // Apresiasi & Partisipasi
        'penghargaan',
        'voting-warga',

        // Profil
        'profil-saya',
    ];

    foreach ($pages as $page) {
        Route::get("/{$page}", [PageController::class, 'show'])->name($page);
    }
});
