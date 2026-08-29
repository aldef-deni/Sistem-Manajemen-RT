<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kartu_keluarga_id')->constrained('kartu_keluarga')->cascadeOnDelete();
            $table->string('nik', 20);
            $table->string('nama_lengkap');
            $table->string('no_hp', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('status_hubungan')->default('Kepala Keluarga');
            $table->string('domisili')->default('Tetap');
            $table->string('role')->default('Warga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
    }
};
