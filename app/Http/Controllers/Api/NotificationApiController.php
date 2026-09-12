<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';
        $query = $request->user()->notifications();
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        $items = $query->latest()->paginate(20);

        return response()->json([
            'belum_dibaca' => $request->user()->unreadNotifications()->count(),
            'data' => collect($items->items())->map(fn (DatabaseNotification $item): array => [
                'id' => $item->id,
                'kategori' => $item->data['category'] ?? 'system',
                'judul' => $item->data['title'] ?? 'Notifikasi',
                'pesan' => $item->data['message'] ?? '',
                'nada' => $item->data['tone'] ?? 'blue',
                'dibaca' => $item->read_at !== null,
                'dibuat_pada' => $item->created_at?->toIso8601String(),
                'tujuan' => $this->mobileTarget($item->data),
            ])->values(),
            'halaman' => [
                'saat_ini' => $items->currentPage(),
                'terakhir' => $items->lastPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function markRead(Request $request, string $notification): JsonResponse
    {
        $item = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();

        return response()->json([
            'pesan' => 'Notifikasi ditandai dibaca.',
            'tujuan' => $this->mobileTarget($item->data),
        ]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['pesan' => 'Semua notifikasi telah ditandai dibaca.']);
    }

    /** @param array<string, mixed> $data */
    private function mobileTarget(array $data): ?string
    {
        $params = is_array($data['route_params'] ?? null) ? $data['route_params'] : [];

        return match ($data['route_name'] ?? null) {
            'chat.show' => isset($params['conversation']) ? '/chat/'.$params['conversation'] : '/chat',
            'subscribe.verifications.index', 'subscribe.payment.index' => '/subscribe',
            'iuran-warga.index', 'pembayaran' => '/iuran',
            'data-warga', 'kartu-keluarga.index' => '/kelola/warga',
            'akun.index' => '/kelola/akun',
            'kas-rt.index' => '/kelola/kas',
            'polling.index', 'polling.show' => '/polling',
            'jadwal-kegiatan.index' => '/jadwal',
            'kegiatan-rt.index', 'umkm.index' => '/(tabs)/informasi',
            'pengumuman.index' => '/(tabs)/informasi',
            'pengumuman.show' => isset($params['pengumuman']) ? '/pengumuman/'.$params['pengumuman'] : '/(tabs)/informasi',
            'pengaduan.index' => '/pengaduan',
            'pengaduan.show' => isset($params['pengaduan']) ? '/pengaduan/'.$params['pengaduan'] : '/pengaduan',
            default => null,
        };
    }
}
