<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribe_payments', function (Blueprint $table) {
            $table->index(
                ['status', 'verified_at'],
                'subscribe_payments_status_verified_at_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('subscribe_payments', function (Blueprint $table) {
            $table->dropIndex('subscribe_payments_status_verified_at_index');
        });
    }
};
