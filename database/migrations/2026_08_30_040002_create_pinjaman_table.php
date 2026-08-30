<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga');
            $table->foreignId('jenis_pinjaman_id')->constrained('jenis_pinjaman');
            $table->decimal('nominal', 15, 2);
            $table->decimal('angsuran_per_bulan', 15, 2)->nullable();
            $table->integer('tenor_bulan');
            $table->text('keperluan');
            $table->text('jaminan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'aktif', 'lunas', 'macet'])->default('pending');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('sisa_pinjaman', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
