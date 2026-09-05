<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\InformasiController;
use App\Http\Controllers\Api\KelolaController;
use App\Http\Controllers\Api\LayananController;
use App\Http\Controllers\Api\ProfilApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API untuk aplikasi mobile
|--------------------------------------------------------------------------
| Memakai token Sanctum. Seluruh balasan berbahasa Indonesia supaya aplikasi
| bisa menampilkannya langsung tanpa penerjemahan.
*/

Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('saya', [AuthController::class, 'saya']);
    Route::put('saya', [ProfilApiController::class, 'perbarui']);
    Route::put('saya/password', [ProfilApiController::class, 'gantiPassword']);
    Route::post('saya/foto', [ProfilApiController::class, 'gantiFoto']);

    Route::get('beranda', [BerandaController::class, 'index']);

    // Informasi
    Route::get('pengumuman', [InformasiController::class, 'pengumuman']);
    Route::get('pengumuman/{pengumuman}', [InformasiController::class, 'pengumumanDetail']);
    Route::get('kegiatan', [InformasiController::class, 'kegiatan']);
    Route::get('kegiatan/{kegiatan}', [InformasiController::class, 'kegiatanDetail']);
    Route::get('jadwal', [InformasiController::class, 'jadwal']);
    Route::get('struktur-rt', [InformasiController::class, 'strukturRT']);
    Route::get('umkm', [InformasiController::class, 'umkm']);

    // Layanan mandiri
    Route::get('iuran-saya', [LayananController::class, 'iuranSaya']);
    Route::get('pengaduan', [LayananController::class, 'pengaduan']);
    Route::post('pengaduan', [LayananController::class, 'kirimPengaduan']);
    Route::get('pengaduan/{pengaduan}', [LayananController::class, 'pengaduanDetail']);
    Route::get('polling', [LayananController::class, 'polling']);
    Route::post('polling/{polling}/pilih', [LayananController::class, 'pilihPolling']);

    /*
    |----------------------------------------------------------------------
    | Pengurus RT ke atas
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,ketua,pengurus')->prefix('kelola')->group(function () {
        Route::get('ringkasan', [KelolaController::class, 'ringkasan']);
        Route::get('warga', [KelolaController::class, 'warga']);
        Route::get('kas', [KelolaController::class, 'kas']);
        Route::get('iuran', [KelolaController::class, 'iuran']);
        Route::patch('iuran/{iuran}/lunas', [KelolaController::class, 'tandaiLunas']);
        Route::patch('pengaduan/{pengaduan}/status', [KelolaController::class, 'ubahStatusPengaduan']);
        Route::post('pengaduan/{pengaduan}/balas', [KelolaController::class, 'balasPengaduan']);
    });

    /*
    |----------------------------------------------------------------------
    | Administrator & Ketua RT
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin,ketua')->prefix('kelola')->group(function () {
        Route::get('akun', [KelolaController::class, 'akun']);
        Route::patch('akun/{akun}/peran', [KelolaController::class, 'ubahPeran']);
        Route::patch('akun/{akun}/reset-password', [KelolaController::class, 'resetPassword']);
    });
});
