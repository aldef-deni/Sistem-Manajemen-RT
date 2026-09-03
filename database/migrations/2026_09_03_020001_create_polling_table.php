<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->json('opsi'); // ['Setuju', 'Tidak', 'Golput']
            $table->boolean('tampilkan_hasil')->default(true);
            $table->boolean('izinkan_ganti')->default(false);
            $table->boolean('anonim')->default(false);
            $table->string('status')->default('aktif'); // aktif, selesai, ditutup
            $table->integer('jumlah_suara')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('polling_vote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('polling_id')->constrained('polling')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pilihan');
            $table->timestamps();

            $table->unique(['polling_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polling_vote');
        Schema::dropIfExists('polling');
    }
};
