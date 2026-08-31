<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('kategori', 50); // Keamanan, Kebersihan, Sosial, Keagamaan, Olahraga, Lainnya
            $table->string('jenis_jadwal', 50); // Harian, Mingguan, Bulanan, Sekali
            $table->string('lokasi')->nullable();
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('anggota_keluarga')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->json('petugas')->nullable(); // Array of petugas/group names
            $table->date('tanggal_mulai');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('aktif'); // aktif, selesai, dibatalkan
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kegiatan');
    }
};
