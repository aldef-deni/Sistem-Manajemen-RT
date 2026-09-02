<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notulen_rapat', function (Blueprint $table) {
            $table->id();
            $table->string('judul_rapat');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('tempat');
            $table->string('tim_proyek')->nullable();
            $table->string('moderator');
            $table->string('notulis');
            $table->text('catatan')->nullable();
            $table->string('status')->default('draft'); // draft, menunggu, final
            $table->integer('dilihat')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('notulen_hadir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulen_rapat_id')->constrained()->onDelete('cascade');
            $table->string('nama_peserta');
            $table->text('ulasan')->nullable();
            $table->boolean('hadir')->default(true);
            $table->timestamps();
        });

        Schema::create('notulen_poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulen_rapat_id')->constrained()->onDelete('cascade');
            $table->string('topik');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notulen_poin');
        Schema::dropIfExists('notulen_hadir');
        Schema::dropIfExists('notulen_rapat');
    }
};
