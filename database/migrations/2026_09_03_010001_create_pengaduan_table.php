<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->string('kategori')->nullable(); // Keamanan, Kebersihan, Keuangan, Infrastruktur, Sosial, Lainnya
            $table->text('isi_pengaduan');
            $table->string('privasi')->default('publik'); // publik, privat
            $table->string('lampiran')->nullable();
            $table->string('status')->default('diterima'); // diterima, diproses, selesai, ditolak
            $table->text('balasan')->nullable();
            $table->string('dibalas_oleh')->nullable();
            $table->timestamp('tanggal_balas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pengaduan_balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('pesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan_balasan');
        Schema::dropIfExists('pengaduan');
    }
};
