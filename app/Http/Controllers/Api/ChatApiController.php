<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChatApiController extends Controller
{
    /** @var list<string> */
    private const CHAT_ROLES = ['ketua', 'pengurus', 'warga'];

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'belum_dibaca' => ChatMessage::unreadCountFor($user),
            'percakapan' => $this->conversationList($user)->map(fn (ChatConversation $item): array => $this->conversationPayload($item, $user))->values(),
            'kontak' => User::query()->whereIn('role', self::CHAT_ROLES)->whereKeyNot($user->id)
                ->orderByRaw("CASE role WHEN 'ketua' THEN 1 WHEN 'pengurus' THEN 2 ELSE 3 END")
                ->orderBy('name')->get()
                ->map(fn (User $item): array => $this->contactPayload($item))->values(),
        ]);
    }

    public function show(Request $request, ChatConversation $conversation): JsonResponse
    {
        $user = $request->user();
        $participant = $this->ensureParticipant($conversation, $user);
        $conversation->load(['users', 'participants.user', 'creator', 'latestMessage.user']);
        $messages = $conversation->messages()->with('user')->latest('id')->limit(80)->get()->reverse()->values();
        $lastId = (int) ($messages->max('id') ?? 0);
        if ($lastId > (int) ($participant->last_read_message_id ?? 0)) {
            $participant->update(['last_read_message_id' => $lastId]);
        }
        $this->markNotificationsRead($user, $conversation);

        return response()->json([
            'percakapan' => $this->conversationPayload($conversation, $user),
            'pemilik_grup' => $participant->role === 'owner',
            'anggota' => $conversation->users->map(fn (User $item): array => $this->contactPayload($item))->values(),
            'pesan' => $messages->map(fn (ChatMessage $item): array => $this->messagePayload($item, $user))->values(),
            'last_message_id' => $lastId,
        ]);
    }

    public function storePrivate(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::notIn([$user->id]), Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES))],
        ], [
            'user_id.required' => 'Pilih warga yang ingin diajak chat.',
            'user_id.not_in' => 'Anda tidak dapat membuat chat dengan diri sendiri.',
            'user_id.exists' => 'Akun yang dipilih tidak tersedia untuk chat.',
        ]);
        $targetId = (int) $data['user_id'];
        $ids = [$user->id, $targetId];
        sort($ids);

        $conversation = DB::transaction(function () use ($user, $targetId, $ids): ChatConversation {
            $item = ChatConversation::query()->firstOrCreate(
                ['private_key' => implode(':', $ids)],
                ['type' => ChatConversation::TYPE_PRIVATE, 'created_by' => $user->id]
            );
            foreach ([$user->id, $targetId] as $participantId) {
                $item->participants()->firstOrCreate(
                    ['user_id' => $participantId],
                    ['role' => 'member', 'joined_at' => now(), 'last_read_message_id' => $item->messages()->max('id')]
                );
            }

            return $item;
        });

        return response()->json(['pesan' => 'Chat siap digunakan.', 'conversation_id' => $conversation->id], 201);
    }

    public function storeGroup(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['integer', 'distinct', Rule::notIn([$user->id]), Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES))],
        ]);

        $conversation = DB::transaction(function () use ($user, $data): ChatConversation {
            $item = ChatConversation::create(['type' => ChatConversation::TYPE_GROUP, 'name' => trim($data['name']), 'created_by' => $user->id]);
            $item->participants()->create(['user_id' => $user->id, 'role' => 'owner', 'joined_at' => now()]);
            foreach (array_unique(array_map('intval', $data['member_ids'])) as $memberId) {
                $item->participants()->create(['user_id' => $memberId, 'role' => 'member', 'joined_at' => now()]);
            }

            return $item;
        });

        return response()->json(['pesan' => 'Grup berhasil dibuat.', 'conversation_id' => $conversation->id], 201);
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $participant = $this->ensureParticipant($conversation, $request->user());
        $data = $request->validate(['after_id' => ['nullable', 'integer', 'min:0']]);
        $messages = $conversation->messages()->with('user')->where('id', '>', (int) ($data['after_id'] ?? 0))->oldest('id')->limit(100)->get();
        $lastId = (int) ($messages->max('id') ?: ($data['after_id'] ?? 0));
        if ($lastId > (int) ($participant->last_read_message_id ?? 0)) {
            $participant->update(['last_read_message_id' => $lastId]);
        }
        $this->markNotificationsRead($request->user(), $conversation);

        return response()->json([
            'pesan' => $messages->map(fn (ChatMessage $item): array => $this->messagePayload($item, $request->user()))->values(),
            'last_message_id' => $lastId,
        ]);
    }

    public function storeMessage(Request $request, ChatConversation $conversation): JsonResponse
    {
        $this->ensureParticipant($conversation, $request->user());
        $data = $request->validate(['body' => ['required', 'string', 'max:4000']]);
        $body = trim($data['body']);
        abort_if($body === '', 422, 'Pesan tidak boleh kosong.');

        $message = DB::transaction(function () use ($conversation, $request, $body): ChatMessage {
            $item = $conversation->messages()->create(['user_id' => $request->user()->id, 'body' => $body]);
            $conversation->update(['last_message_at' => $item->created_at]);
            $conversation->participants()->where('user_id', $request->user()->id)->update(['last_read_message_id' => $item->id]);

            return $item->load('user');
        });

        $recipients = $conversation->users()->where('users.id', '!=', $request->user()->id)->whereIn('users.role', self::CHAT_ROLES)->get();
        Notification::send($recipients, new SystemNotification(
            category: 'chat',
            title: $conversation->isGroup() ? 'Pesan baru di '.$conversation->displayName($request->user()) : 'Pesan baru dari '.$request->user()->name,
            message: $conversation->isGroup() ? $request->user()->name.': '.Str::limit($body, 120) : Str::limit($body, 120),
            routeName: 'chat.show', routeParams: ['conversation' => $conversation->id],
            tone: $conversation->isGroup() ? 'violet' : 'blue', context: ['conversation_id' => $conversation->id],
        ));

        return response()->json(['pesan' => $this->messagePayload($message, $request->user())], 201);
    }

    public function updateGroup(Request $request, ChatConversation $conversation): JsonResponse
    {
        $this->ensureGroupOwner($conversation, $request->user());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'distinct', Rule::notIn([$request->user()->id]), Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES))],
        ]);
        DB::transaction(function () use ($conversation, $request, $data): void {
            $ownerId = $request->user()->id;
            $ids = collect($data['member_ids'] ?? [])->map(fn ($id): int => (int) $id)->push($ownerId)->unique()->values();
            $latestId = $conversation->messages()->max('id');
            $conversation->update(['name' => trim($data['name'])]);
            $conversation->participants()->where('user_id', '!=', $ownerId)->whereNotIn('user_id', $ids->all())->delete();
            foreach ($ids as $id) {
                $conversation->participants()->firstOrCreate(
                    ['user_id' => $id],
                    ['role' => $id === $ownerId ? 'owner' : 'member', 'joined_at' => now(), 'last_read_message_id' => $latestId]
                );
            }
        });

        return response()->json(['pesan' => 'Grup berhasil diperbarui.']);
    }

    public function leave(Request $request, ChatConversation $conversation): JsonResponse
    {
        $participant = $this->ensureParticipant($conversation, $request->user());
        abort_unless($conversation->isGroup(), 422, 'Chat pribadi tidak dapat ditinggalkan.');
        DB::transaction(function () use ($conversation, $participant): void {
            $remaining = $conversation->participants()->where('id', '!=', $participant->id)->oldest('id')->get();
            if ($remaining->isEmpty()) {
                $conversation->delete();
                return;
            }
            if ($participant->role === 'owner') {
                $newOwner = $remaining->first();
                $newOwner->update(['role' => 'owner']);
                $conversation->update(['created_by' => $newOwner->user_id]);
            }
            $participant->delete();
        });

        return response()->json(['pesan' => 'Anda telah keluar dari grup.']);
    }

    /** @return Collection<int, ChatConversation> */
    private function conversationList(User $user): Collection
    {
        $items = ChatConversation::query()->whereHas('participants', fn ($query) => $query->where('user_id', $user->id))
            ->with(['users', 'participants', 'latestMessage.user'])->orderByRaw('COALESCE(last_message_at, created_at) DESC')->get();
        $unread = ChatMessage::query()->select('chat_messages.conversation_id')->selectRaw('COUNT(*) as aggregate')
            ->join('chat_participants as reader', function ($join) use ($user): void {
                $join->on('reader.conversation_id', '=', 'chat_messages.conversation_id')->where('reader.user_id', '=', $user->id);
            })
            ->where(fn ($query) => $query->whereNull('chat_messages.user_id')->orWhere('chat_messages.user_id', '!=', $user->id))
            ->whereRaw('chat_messages.id > COALESCE(reader.last_read_message_id, 0)')
            ->groupBy('chat_messages.conversation_id')->pluck('aggregate', 'chat_messages.conversation_id');

        return $items->each(fn (ChatConversation $item) => $item->setAttribute('unread_count', (int) ($unread[$item->id] ?? 0)));
    }

    private function ensureParticipant(ChatConversation $conversation, User $user): ChatParticipant
    {
        $item = $conversation->participants()->where('user_id', $user->id)->first();
        abort_unless($item, 404);
        return $item;
    }

    private function ensureGroupOwner(ChatConversation $conversation, User $user): ChatParticipant
    {
        abort_unless($conversation->isGroup(), 422, 'Percakapan ini bukan grup.');
        $item = $this->ensureParticipant($conversation, $user);
        abort_unless($item->role === 'owner', 403, 'Hanya pembuat grup yang dapat mengelola anggota.');
        return $item;
    }

    private function markNotificationsRead(User $user, ChatConversation $conversation): void
    {
        $user->unreadNotifications()->where('data->category', 'chat')->where('data->conversation_id', $conversation->id)->update(['read_at' => now()]);
    }

    private function contactPayload(User $item): array
    {
        return ['id' => $item->id, 'nama' => $item->name, 'peran' => $item->role, 'peran_label' => $item->role_label, 'foto_url' => $item->foto_url];
    }

    private function conversationPayload(ChatConversation $item, User $viewer): array
    {
        $item->loadMissing(['users', 'participants', 'latestMessage.user']);
        $other = $item->isGroup() ? null : $item->otherUser($viewer);

        return [
            'id' => $item->id,
            'tipe' => $item->type,
            'nama' => $item->displayName($viewer),
            'foto_url' => $other?->foto_url,
            'inisial' => $item->isGroup() ? strtoupper(substr($item->name ?: 'G', 0, 1)) : ($other?->initial ?? '?'),
            'jumlah_anggota' => $item->users->count(),
            'belum_dibaca' => (int) ($item->unread_count ?? 0),
            'pesan_terakhir' => $item->latestMessage?->body,
            'pengirim_terakhir' => $item->latestMessage?->user?->name,
            'waktu_terakhir' => ($item->last_message_at ?: $item->created_at)?->toIso8601String(),
        ];
    }

    private function messagePayload(ChatMessage $item, User $viewer): array
    {
        return [
            'id' => $item->id, 'isi' => $item->body, 'user_id' => $item->user_id,
            'nama' => $item->user?->name ?? 'Akun dihapus', 'inisial' => $item->user?->initial ?? '?',
            'foto_url' => $item->user?->foto_url, 'peran' => $item->user?->role_label ?? 'Tidak tersedia',
            'milik_saya' => $item->user_id === $viewer->id, 'jam' => $item->created_at->format('H:i'),
            'dikirim_pada' => $item->created_at->toIso8601String(),
        ];
    }
}
