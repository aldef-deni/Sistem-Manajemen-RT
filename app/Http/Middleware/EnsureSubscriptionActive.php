<?php

namespace App\Http\Middleware;

use App\Support\SubscriptionAccess;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function __construct(private readonly SubscriptionAccess $subscription) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $this->subscription->protects($request)) {
            return $next($request);
        }

        $status = $this->subscription->status($user);

        if (! $status['locked']) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return new JsonResponse([
                'message' => 'Akses memerlukan subscribe aktif.',
                'code' => 'SUBSCRIPTION_REQUIRED',
                'subscription_status' => $status['state'],
                'payment_url' => route('subscribe.payment.index'),
            ], 402);
        }

        return redirect()
            ->route('dashboard')
            ->with('show_subscribe_modal', true);
    }
}
