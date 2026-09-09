<?php

namespace App\Http\Controllers;

use App\Models\SubscribePayment;
use App\Support\SubscriptionAccess;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, SubscriptionAccess $subscription)
    {
        $user = $request->user();
        $subscriptionStatus = $subscription->status($user);
        $subscriptionPendingCount = $user->role === 'admin'
            ? SubscribePayment::where('status', SubscribePayment::STATUS_PENDING)->count()
            : 0;

        return view('dashboard', compact('subscriptionStatus', 'subscriptionPendingCount'));
    }
}
