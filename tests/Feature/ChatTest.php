<?php

namespace Tests\Feature;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\SettingRT;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
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

    public function test_chat_hanya_dapat_diakses_warga_pengurus_dan_ketua(): void
    {
        foreach (['warga', 'pengurus', 'ketua'] as $role) {
            $this->actingAs($this->user($role, 'Akun '.ucfirst($role)))
                ->get(route('chat.index'))
                ->assertOk()
                ->assertSee('Chat Warga')
                ->assertSee('Chat pribadi');
        }

        $this->actingAs($this->user('admin', 'Administrator Chat'))
            ->get(route('chat.index'))
            ->assertForbidden();
    }

    public function test_chat_tetap_dapat_dibuka_saat_subscribe_pengguna_terkunci(): void
    {
        $warga = $this->user('warga', 'Warga Belum Subscribe');
        SettingRT::set('subscribe_enabled', '1');
        SettingRT::set('subscribe_roles', json_encode(['warga'], JSON_THROW_ON_ERROR));

        $this->actingAs($warga)
            ->get(route('chat.index'))
            ->assertOk();
    }

    public function test_japri_dibuat_sekali_dan_hanya_berisi_dua_peserta(): void
    {
        $warga = $this->user('warga', 'Warga Satu');
        $pengurus = $this->user('pengurus', 'Pengurus Satu');

        $this->actingAs($warga)
            ->post(route('chat.private.store'), ['user_id' => $pengurus->id])
            ->assertRedirect();

        $conversation = ChatConversation::query()->firstOrFail();
        $this->assertSame(ChatConversation::TYPE_PRIVATE, $conversation->type);
        $this->assertCount(2, $conversation->participants);

        $this->actingAs($pengurus)
            ->post(route('chat.private.store'), ['user_id' => $warga->id])
            ->assertRedirect(route('chat.show', $conversation));

        $this->assertSame(1, ChatConversation::query()->count());
        $this->assertSame(2, ChatParticipant::query()->count());
    }

    public function test_peserta_dapat_mengirim_dan_membaca_pesan_tetapi_nonanggota_ditolak(): void
    {
        $warga = $this->user('warga', 'Warga Pengirim');
        $ketua = $this->user('ketua', 'Ketua Penerima');
        $orangLain = $this->user('warga', 'Warga Lain');

        $this->actingAs($warga)->post(route('chat.private.store'), ['user_id' => $ketua->id]);
        $conversation = ChatConversation::query()->firstOrFail();

        $response = $this->actingAs($warga)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Selamat pagi, Pak Ketua.']);

        $response->assertCreated()
            ->assertJsonPath('message.body', 'Selamat pagi, Pak Ketua.')
            ->assertJsonPath('message.is_mine', true);
        $message = ChatMessage::query()->firstOrFail();

        $this->actingAs($orangLain)
            ->get(route('chat.show', $conversation))
            ->assertNotFound();
        $this->actingAs($orangLain)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Mencoba masuk'])
            ->assertNotFound();

        $this->actingAs($ketua)
            ->get(route('chat.show', $conversation))
            ->assertOk()
            ->assertSee('Selamat pagi, Pak Ketua.');

        $this->assertSame(
            $message->id,
            ChatParticipant::query()
                ->where('conversation_id', $conversation->id)
                ->where('user_id', $ketua->id)
                ->value('last_read_message_id')
        );
    }

    public function test_grup_dapat_dibuat_dikelola_dan_digunakan_semua_anggotanya(): void
    {
        $ketua = $this->user('ketua', 'Ketua Grup');
        $pengurus = $this->user('pengurus', 'Pengurus Grup');
        $warga = $this->user('warga', 'Warga Grup');
        $calonAnggota = $this->user('warga', 'Warga Baru');

        $this->actingAs($ketua)
            ->post(route('chat.groups.store'), [
                'name' => 'Koordinasi Lingkungan',
                'member_ids' => [$pengurus->id, $warga->id],
            ])
            ->assertRedirect();

        $conversation = ChatConversation::query()->firstOrFail();
        $this->assertTrue($conversation->isGroup());
        $this->assertSame(3, $conversation->participants()->count());
        $this->assertSame('owner', $conversation->participants()->where('user_id', $ketua->id)->value('role'));

        $this->actingAs($pengurus)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Siap koordinasi.'])
            ->assertCreated();

        $this->actingAs($pengurus)
            ->put(route('chat.groups.update', $conversation), [
                'name' => 'Tidak Diizinkan',
                'member_ids' => [$warga->id],
            ])
            ->assertForbidden();

        $this->actingAs($ketua)
            ->put(route('chat.groups.update', $conversation), [
                'name' => 'Koordinasi Blok A',
                'member_ids' => [$warga->id, $calonAnggota->id],
            ])
            ->assertRedirect(route('chat.show', $conversation));

        $this->assertSame('Koordinasi Blok A', $conversation->fresh()->name);
        $this->assertFalse($conversation->participants()->where('user_id', $pengurus->id)->exists());
        $this->assertTrue($conversation->participants()->where('user_id', $calonAnggota->id)->exists());
        $this->actingAs($pengurus)->get(route('chat.show', $conversation))->assertNotFound();
        $this->actingAs($calonAnggota)->get(route('chat.show', $conversation))->assertOk();
    }

    public function test_pesan_baru_muncul_sebagai_belum_dibaca_dan_polling_menandainya_dibaca(): void
    {
        $pengirim = $this->user('warga', 'Pengirim Notifikasi');
        $penerima = $this->user('pengurus', 'Penerima Notifikasi');

        $this->actingAs($pengirim)->post(route('chat.private.store'), ['user_id' => $penerima->id]);
        $conversation = ChatConversation::query()->firstOrFail();
        $this->actingAs($pengirim)
            ->postJson(route('chat.messages.store', $conversation), ['body' => 'Pesan belum dibaca'])
            ->assertCreated();

        $this->actingAs($penerima)
            ->get(route('chat.index'))
            ->assertOk()
            ->assertSee('Pesan belum dibaca');

        $message = ChatMessage::query()->firstOrFail();
        $this->actingAs($penerima)
            ->getJson(route('chat.messages.index', $conversation).'?after_id=0')
            ->assertOk()
            ->assertJsonPath('messages.0.body', 'Pesan belum dibaca');

        $this->assertSame(
            $message->id,
            ChatParticipant::query()
                ->where('conversation_id', $conversation->id)
                ->where('user_id', $penerima->id)
                ->value('last_read_message_id')
        );
    }
}
