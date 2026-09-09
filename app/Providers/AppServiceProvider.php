<?php

namespace App\Providers;

use App\Models\SubscribePayment;
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
        View::composer('layouts.app', function ($view): void {
            $user = auth()->user();
            $subscriptionStatus = null;
            $subscriptionPendingCount = 0;

            if ($user && Schema::hasTable('setting_rt') && Schema::hasTable('subscribe_payments')) {
                $subscriptionStatus = app(SubscriptionAccess::class)->status($user);

                if ($user->role === 'admin') {
                    $subscriptionPendingCount = SubscribePayment::query()
                        ->where('status', SubscribePayment::STATUS_PENDING)
                        ->count();
                }
            }

            $view->with(compact('subscriptionStatus', 'subscriptionPendingCount'));
        });
    }
}
