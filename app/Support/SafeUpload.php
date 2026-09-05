<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Menyimpan berkas unggahan ke dalam public/uploads dengan nama yang dibuat
 * sendiri oleh aplikasi.
 *
 * Ekstensi dari pengunggah tidak pernah dipakai apa adanya. Berkas hanya
 * disimpan bila ekstensinya — atau ekstensi hasil pembacaan isi berkas —
 * ada di daftar yang diizinkan. Tanpa penjagaan ini sebuah berkas .php bisa
 * mendarat di dalam public/ dan dijalankan langsung oleh nginx.
 */
class SafeUpload
{
    /** Berkas gambar saja. */
    public const IMAGE = ['jpg', 'jpeg', 'png', 'webp'];

    /** Gambar dan PDF — untuk lampiran, bukti, dan dokumen pindaian. */
    public const DOCUMENT = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    /** Dokumen perkantoran, dipakai lampiran pengumuman. */
    public const OFFICE = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'];

    /**
     * @param  string  $folder  subfolder di dalam public/uploads
     * @param  string  $prefix  awalan nama berkas, mis. "kk" atau "foto"
     * @return string  path relatif untuk disimpan ke database
     */
    public static function store(
        UploadedFile $file,
        string $folder,
        string $prefix,
        array $allowed = self::DOCUMENT
    ): string {
        $ext = self::resolveExtension($file, $allowed);

        $name = $prefix . '_' . now()->format('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dir  = public_path('uploads/' . $folder);

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file->move($dir, $name);

        return 'uploads/' . $folder . '/' . $name;
    }

    /**
     * Hapus berkas lama bila ada. Path di luar public/uploads diabaikan
     * supaya nilai database yang aneh tidak bisa menghapus berkas lain.
     */
    public static function delete(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        $full = public_path($path);

        if (is_file($full)) {
            @unlink($full);
        }
    }

    private static function resolveExtension(UploadedFile $file, array $allowed): string
    {
        $ext = self::normalize($file->getClientOriginalExtension());

        if (in_array($ext, $allowed, true)) {
            return $ext;
        }

        // Ekstensi dari pengunggah tidak dikenal — coba baca dari isi berkasnya.
        $guessed = self::normalize((string) $file->guessExtension());

        if (in_array($guessed, $allowed, true)) {
            return $guessed;
        }

        abort(422, 'Jenis berkas tidak diizinkan.');
    }

    private static function normalize(string $ext): string
    {
        $ext = strtolower(trim($ext));

        return $ext === 'jpeg' ? 'jpg' : $ext;
    }
}
