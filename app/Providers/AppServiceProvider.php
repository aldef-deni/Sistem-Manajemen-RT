<?php

namespace App\Providers;

use App\Models\AnggotaKeluarga;
use App\Models\ChatMessage;
use App\Models\IuranWarga;
use App\Models\JadwalKegiatan;
use App\Models\KartuKeluarga;
use App\Models\KegiatanRT;
use App\Models\SubscribePayment;
use App\Models\TransaksiKas;
use App\Models\UMKM;
use App\Models\User;
use App\Observers\ManagementAuditObserver;
use App\Support\SubscriptionAccess;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            AnggotaKeluarga::class,
            KartuKeluarga::class,
            IuranWarga::class,
            TransaksiKas::class,
            KegiatanRT::class,
            JadwalKegiatan::class,
            UMKM::class,
            User::class,
        ] as $model) {
            $model::observe(ManagementAuditObserver::class);
        }

        View::composer('layouts.app', function ($view): void {
            $user = auth()->user();
            $subscriptionStatus = null;
            $subscriptionPendingCount = 0;
            $chatUnreadCount = 0;
            $notificationItems = collect();
            $notificationUnreadCount = 0;

            if ($user && Schema::hasTable('setting_rt') && Schema::hasTable('subscribe_payments')) {
                $subscriptionStatus = app(SubscriptionAccess::class)->status($user);

                if ($user->role === 'admin') {
                    $subscriptionPendingCount = SubscribePayment::query()
                        ->where('status', SubscribePayment::STATUS_PENDING)
                        ->count();
                }
            }

            if (
                $user
                && in_array($user->role, ['ketua', 'pengurus', 'warga'], true)
                && Schema::hasTable('chat_messages')
                && Schema::hasTable('chat_participants')
            ) {
                $chatUnreadCount = ChatMessage::unreadCountFor($user);
            }

            if ($user && Schema::hasTable('notifications')) {
                $notificationItems = $user->notifications()->latest()->limit(5)->get();
                $notificationUnreadCount = $user->unreadNotifications()->count();
            }

            $view->with(compact(
                'subscriptionStatus',
                'subscriptionPendingCount',
                'chatUnreadCount',
                'notificationItems',
                'notificationUnreadCount',
            ));
        });
    }
}
