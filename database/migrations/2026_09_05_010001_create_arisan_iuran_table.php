<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan pembayaran iuran arisan, satu baris untuk satu peserta pada
     * satu periode. Sebelum tabel ini ada, tombol bayar iuran hanya
     * menampilkan pesan sukses tanpa menyimpan apa pun.
     */
    public function up(): void
    {
        Schema::create('arisan_iuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arisan_id')->constrained('arisan')->cascadeOnDelete();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga')->cascadeOnDelete();

            // Pertemuan ke berapa, dihitung dari tanggal_mulai arisan.
            $table->unsignedInteger('periode_ke');

            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->enum('metode', ['tunai', 'transfer'])->default('tunai');
            $table->string('keterangan', 255)->nullable();

            // Pengurus yang mencatat, untuk jejak audit.
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Satu peserta hanya boleh punya satu catatan per periode.
            $table->unique(['arisan_id', 'anggota_keluarga_id', 'periode_ke'], 'arisan_iuran_unik');
            $table->index(['arisan_id', 'periode_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arisan_iuran');
    }
};
