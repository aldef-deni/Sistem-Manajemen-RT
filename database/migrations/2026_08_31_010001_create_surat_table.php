<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat', 30)->unique();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga');
            $table->string('nama_pemohon');
            $table->string('nik')->nullable();
            $table->string('jenis_surat'); // Surat Keterangan Domisili, Surat Keterangan Usaha, Surat Keterangan Tidak Mampu, Surat Pengantar, SKCK, Surat Keterangan Kelakuan Baik, Lainnya
            $table->text('keperluan');
            $table->string('file_dokumen')->nullable(); // path to uploaded file
            $table->string('status')->default('pending'); // pending, diproses, selesai, ditolak
            $table->text('catatan_admin')->nullable();
            $table->string('nomor_surat')->nullable(); // nomor surat resmi yang diterbitkan
            $table->date('tanggal_proses')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_surat_jadi')->nullable(); // file surat yang sudah jadi (PDF)
            $table->integer('diproses_oleh')->unsigned()->nullable(); // user id
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
