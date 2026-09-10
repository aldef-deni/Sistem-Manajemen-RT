<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        $this->backfillPendingSubscribeNotifications();
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }

    private function backfillPendingSubscribeNotifications(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('subscribe_payments')) {
            return;
        }

        $adminIds = DB::table('users')->where('role', 'admin')->pluck('id');
        $payments = DB::table('subscribe_payments')
            ->join('users', 'users.id', '=', 'subscribe_payments.user_id')
            ->where('subscribe_payments.status', 'pending')
            ->get(['subscribe_payments.id', 'users.name']);
        $now = now();

        foreach ($adminIds as $adminId) {
            foreach ($payments as $payment) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\SystemNotification',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id' => $adminId,
                    'data' => json_encode([
                        'category' => 'subscribe',
                        'title' => 'Pembayaran subscribe baru',
                        'message' => $payment->name.' menunggu verifikasi pembayaran.',
                        'route_name' => 'subscribe.verifications.index',
                        'route_params' => ['status' => 'pending'],
                        'tone' => 'amber',
                        'payment_id' => $payment->id,
                    ], JSON_THROW_ON_ERROR),
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
};
