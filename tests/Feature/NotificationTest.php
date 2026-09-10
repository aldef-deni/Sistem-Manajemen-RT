<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $name): User
    {
        return User::factory()->create([
            'name' => $name,
            'username' => strtolower(str_replace(' ', '.', $name)).'.'.uniqid(),
            'role' => $role,
        ]);
    }

    private function notify(User $user, string $title, string $routeName = 'pembayaran'): void
    {
        $user->notify(new SystemNotification(
            category: 'system',
            title: $title,
            message: 'Isi '.$title,
            routeName: $routeName,
        ));
    }

    public function test_bell_dan_halaman_notifikasi_tersedia_untuk_semua_role(): void
    {
        foreach (['admin', 'ketua', 'pengurus', 'warga'] as $role) {
            $user = $this->user($role, 'Notifikasi '.ucfirst($role));

            $this->actingAs($user)
                ->get(route('notifications.index'))
                ->assertOk()
                ->assertSee('Pusat Notifikasi')
                ->assertSee('notificationToggle', false);
        }
    }

    public function test_dropdown_hanya_menampilkan_lima_notifikasi_terbaru(): void
    {
        $user = $this->user('admin', 'Admin Lima Notifikasi');

        foreach (range(1, 6) as $number) {
            $this->notify($user, 'Notifikasi nomor '.$number);
            $this->travel(1)->second();
        }

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Notifikasi nomor 6')
            ->assertSee('Notifikasi nomor 2')
            ->assertDontSee('Notifikasi nomor 1')
            ->assertSee('Lihat semua notifikasi');
    }

    public function test_membuka_notifikasi_menandainya_dibaca_dan_redirect_ke_halaman_tujuan(): void
    {
        $user = $this->user('warga', 'Warga Tujuan Notifikasi');
        $this->notify($user, 'Buka halaman pembayaran');
        $notification = $user->notifications()->firstOrFail();

        $this->actingAs($user)
            ->get(route('notifications.open', $notification->id))
            ->assertRedirect(route('pembayaran'));

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_pengguna_tidak_dapat_membuka_notifikasi_milik_akun_lain(): void
    {
        $owner = $this->user('warga', 'Pemilik Notifikasi');
        $other = $this->user('warga', 'Akun Lain Notifikasi');
        $this->notify($owner, 'Notifikasi privat');

        $this->actingAs($other)
            ->get(route('notifications.open', $owner->notifications()->firstOrFail()->id))
            ->assertNotFound();
    }

    public function test_semua_notifikasi_dapat_ditandai_dibaca_sekaligus(): void
    {
        $user = $this->user('pengurus', 'Pengurus Baca Semua');
        $this->notify($user, 'Notifikasi pertama');
        $this->notify($user, 'Notifikasi kedua');

        $this->actingAs($user)
            ->from(route('notifications.index'))
            ->post(route('notifications.read-all'))
            ->assertRedirect(route('notifications.index'))
            ->assertSessionHas('success');

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_pesan_chat_membuat_notifikasi_untuk_penerima(): void
    {
        $sender = $this->user('warga', 'Pengirim Bell');
        $recipient = $this->user('ketua', 'Penerima Bell');

        $this->actingAs($sender)
            ->post(route('chat.private.store'), ['user_id' => $recipient->id]);
        $conversation = ChatConversation::query()->firstOrFail();

        $this->actingAs($sender)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Mohon cek jadwal ronda.'])
            ->assertCreated();

        $notification = $recipient->notifications()->firstOrFail();
        $this->assertSame('chat', $notification->data['category']);
        $this->assertSame('chat.show', $notification->data['route_name']);
        $this->assertSame($conversation->id, $notification->data['conversation_id']);
        $this->assertStringContainsString('jadwal ronda', $notification->data['message']);
    }
}
