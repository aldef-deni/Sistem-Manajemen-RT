<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga')->cascadeOnDelete();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->json('jenis_bantuan'); // ['BLT','Sembako','PKH','BPNT','Lansia','Lainnya']
            $table->integer('tahun');
            $table->string('status')->default('aktif'); // aktif, nonaktif
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_bantuan');
    }
};
