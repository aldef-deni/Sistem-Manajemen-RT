<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga');
            $table->string('no_rekening')->unique();
            $table->enum('jenis_tabungan', ['sukarela', 'wajib', 'investasi'])->default('sukarela');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'blokir'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabungan');
    }
};
