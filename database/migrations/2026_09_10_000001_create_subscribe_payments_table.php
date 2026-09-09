<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribe_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('destination_bank', 100);
            $table->string('destination_account_number', 50);
            $table->string('destination_account_name', 150);
            $table->string('sender_bank', 100);
            $table->string('sender_account_name', 150);
            $table->date('paid_at');
            $table->string('proof_path');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('pending_key')->nullable()->unique();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'ends_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribe_payments');
    }
};
