<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // TAB-BRI, BRI, BCA, MANDIRI, TUNAI, DANA
            $table->decimal('saldo', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening_kas');
    }
};
