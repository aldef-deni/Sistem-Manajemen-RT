<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 20)->unique();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->text('alamat');
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
