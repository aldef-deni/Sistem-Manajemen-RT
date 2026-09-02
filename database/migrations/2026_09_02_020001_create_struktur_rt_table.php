<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_rt', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rt');
            $table->string('nomor_rt');
            $table->string('nomor_rw');
            $table->string('alamat_rt');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kode_pos')->nullable();
            $table->string('telepon_rt')->nullable();
            $table->string('email_rt')->nullable();
            $table->text('logo_rt')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('peraturan')->nullable();
            $table->timestamps();
        });

        Schema::create('pengurus_rt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('struktur_rt_id');
            $table->foreign('struktur_rt_id')->references('id')->on('struktur_rt')->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('foto')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('aktif'); // aktif, tidak_aktif
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('setting_rt', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus_rt');
        Schema::dropIfExists('setting_rt');
        Schema::dropIfExists('struktur_rt');
    }
};
