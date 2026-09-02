<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_rt', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('artikel');
            $table->string('kategori'); // Umum, Keagamaan, Kebersihan, Keamanan, Olahraga, Sosial, Lainnya
            $table->string('status')->default('draft'); // draft, publish, arsip
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('foto_utama')->nullable();
            $table->json('galeri_foto')->nullable();
            $table->integer('dilihat')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_rt');
    }
};
