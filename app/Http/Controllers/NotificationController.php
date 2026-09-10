<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /** @var list<string> */
    private const ALLOWED_ROUTES = [
        'chat.show',
        'subscribe.verifications.index',
        'subscribe.payment.index',
        'pembayaran',
        'iuran-warga.index',
        'pengumuman.show',
        'pengaduan.show',
    ];

    public function index(Request $request): View
    {
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';
        $query = $request->user()->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();

        return view('notifications.index', compact('notifications', 'filter'));
    }

    public function open(Request $request, string $notification): RedirectResponse
    {
        /** @var DatabaseNotification $item */
        $item = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();

        $routeName = $item->data['route_name'] ?? null;
        $routeParams = $item->data['route_params'] ?? [];

        if (! is_string($routeName)
            || ! in_array($routeName, self::ALLOWED_ROUTES, true)
            || ! Route::has($routeName)
            || ! is_array($routeParams)) {
            return redirect()->route('notifications.index');
        }

        return redirect()->route($routeName, $routeParams);
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
