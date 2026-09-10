<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChatController extends Controller
{
    /** @var list<string> */
    private const CHAT_ROLES = ['ketua', 'pengurus', 'warga'];

    public function index(Request $request): View
    {
        return $this->renderChat($request);
    }

    public function show(Request $request, ChatConversation $conversation): View
    {
        $this->ensureParticipant($conversation, $request->user());

        return $this->renderChat($request, $conversation);
    }

    public function storePrivate(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                Rule::notIn([$user->id]),
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES)),
            ],
        ], [
            'user_id.required' => 'Pilih warga yang ingin diajak chat.',
            'user_id.not_in' => 'Anda tidak dapat membuat chat dengan diri sendiri.',
            'user_id.exists' => 'Akun yang dipilih tidak tersedia untuk chat.',
        ]);

        $targetId = (int) $validated['user_id'];
        $ids = [$user->id, $targetId];
        sort($ids);
        $privateKey = implode(':', $ids);

        $conversation = DB::transaction(function () use ($user, $targetId, $privateKey): ChatConversation {
            $conversation = ChatConversation::query()->firstOrCreate(
                ['private_key' => $privateKey],
                [
                    'type' => ChatConversation::TYPE_PRIVATE,
                    'created_by' => $user->id,
                ]
            );

            foreach ([$user->id, $targetId] as $participantId) {
                $conversation->participants()->firstOrCreate(
                    ['user_id' => $participantId],
                    [
                        'role' => 'member',
                        'joined_at' => now(),
                        'last_read_message_id' => $conversation->messages()->max('id'),
                    ]
                );
            }

            return $conversation;
        });

        return redirect()->route('chat.show', $conversation);
    }

    public function storeGroup(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => [
                'integer',
                'distinct',
                Rule::notIn([$user->id]),
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES)),
            ],
        ], [
            'name.required' => 'Nama grup wajib diisi.',
            'member_ids.required' => 'Pilih minimal satu anggota grup.',
            'member_ids.min' => 'Pilih minimal satu anggota grup.',
            'member_ids.*.exists' => 'Salah satu akun tidak tersedia untuk chat.',
        ]);

        $conversation = DB::transaction(function () use ($user, $validated): ChatConversation {
            $conversation = ChatConversation::create([
                'type' => ChatConversation::TYPE_GROUP,
                'name' => trim($validated['name']),
                'created_by' => $user->id,
            ]);

            $conversation->participants()->create([
                'user_id' => $user->id,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            foreach (array_unique(array_map('intval', $validated['member_ids'])) as $memberId) {
                $conversation->participants()->create([
                    'user_id' => $memberId,
                    'role' => 'member',
                    'joined_at' => now(),
                ]);
            }

            return $conversation;
        });

        return redirect()
            ->route('chat.show', $conversation)
            ->with('success', 'Grup berhasil dibuat. Silakan mulai percakapan.');
    }

    public function updateGroup(Request $request, ChatConversation $conversation): RedirectResponse
    {
        $this->ensureGroupOwner($conversation, $request->user());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => [
                'integer',
                'distinct',
                Rule::notIn([$request->user()->id]),
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', self::CHAT_ROLES)),
            ],
        ], [
            'name.required' => 'Nama grup wajib diisi.',
            'member_ids.*.exists' => 'Salah satu akun tidak tersedia untuk chat.',
        ]);

        DB::transaction(function () use ($conversation, $request, $validated): void {
            $ownerId = $request->user()->id;
            $memberIds = collect($validated['member_ids'] ?? [])
                ->map(fn ($id): int => (int) $id)
                ->push($ownerId)
                ->unique()
                ->values();
            $latestMessageId = $conversation->messages()->max('id');

            $conversation->update(['name' => trim($validated['name'])]);

            $conversation->participants()
                ->where('user_id', '!=', $ownerId)
                ->whereNotIn('user_id', $memberIds->all())
                ->delete();

            foreach ($memberIds as $memberId) {
                $conversation->participants()->firstOrCreate(
                    ['user_id' => $memberId],
                    [
                        'role' => $memberId === $ownerId ? 'owner' : 'member',
                        'joined_at' => now(),
                        'last_read_message_id' => $latestMessageId,
                    ]
                );
            }
        });

        return redirect()
            ->route('chat.show', $conversation)
            ->with('success', 'Informasi dan anggota grup berhasil diperbarui.');
    }

    public function leave(Request $request, ChatConversation $conversation): RedirectResponse
    {
        $participant = $this->ensureParticipant($conversation, $request->user());

        abort_unless($conversation->isGroup(), 422, 'Chat pribadi tidak dapat ditinggalkan.');

        DB::transaction(function () use ($conversation, $participant): void {
            $remaining = $conversation->participants()
                ->where('id', '!=', $participant->id)
                ->oldest('id')
                ->get();

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

        return redirect()->route('chat.index')->with('success', 'Anda telah keluar dari grup.');
    }

    public function storeMessage(Request $request, ChatConversation $conversation): JsonResponse|RedirectResponse
    {
        $this->ensureParticipant($conversation, $request->user());

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ], [
            'body.required' => 'Pesan tidak boleh kosong.',
            'body.max' => 'Pesan maksimal 4.000 karakter.',
        ]);

        $body = trim($validated['body']);

        if ($body === '') {
            return response()->json(['message' => 'Pesan tidak boleh kosong.'], 422);
        }

        $message = DB::transaction(function () use ($conversation, $request, $body): ChatMessage {
            $message = $conversation->messages()->create([
                'user_id' => $request->user()->id,
                'body' => $body,
            ]);

            $conversation->update(['last_message_at' => $message->created_at]);
            $conversation->participants()
                ->where('user_id', $request->user()->id)
                ->update(['last_read_message_id' => $message->id]);

            return $message->load('user');
        });

        $recipients = $conversation->users()
            ->where('users.id', '!=', $request->user()->id)
            ->whereIn('users.role', self::CHAT_ROLES)
            ->get();

        Notification::send($recipients, new SystemNotification(
            category: 'chat',
            title: $conversation->isGroup()
                ? 'Pesan baru di '.$conversation->displayName($request->user())
                : 'Pesan baru dari '.$request->user()->name,
            message: $conversation->isGroup()
                ? $request->user()->name.': '.Str::limit($body, 120)
                : Str::limit($body, 120),
            routeName: 'chat.show',
            routeParams: ['conversation' => $conversation->id],
            tone: $conversation->isGroup() ? 'violet' : 'blue',
            context: ['conversation_id' => $conversation->id],
        ));

        if (! $request->expectsJson()) {
            return redirect()->route('chat.show', $conversation);
        }

        return response()->json([
            'message' => $this->messagePayload($message, $request->user()),
        ], 201);
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $participant = $this->ensureParticipant($conversation, $request->user());
        $validated = $request->validate([
            'after_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $messages = $conversation->messages()
            ->with('user')
            ->where('id', '>', (int) ($validated['after_id'] ?? 0))
            ->oldest('id')
            ->limit(100)
            ->get();

        $lastMessageId = $messages->max('id');
        if ($lastMessageId && $lastMessageId > ($participant->last_read_message_id ?? 0)) {
            $participant->update(['last_read_message_id' => $lastMessageId]);
        }
        $this->markChatNotificationsAsRead($request->user(), $conversation);

        return response()->json([
            'messages' => $messages
                ->map(fn (ChatMessage $message): array => $this->messagePayload($message, $request->user()))
                ->values(),
            'last_message_id' => $lastMessageId ?: (int) ($validated['after_id'] ?? 0),
        ]);
    }

    private function renderChat(Request $request, ?ChatConversation $activeConversation = null): View
    {
        $user = $request->user();
        $initialMessages = collect();
        $activeParticipant = null;

        if ($activeConversation) {
            $activeConversation->load(['users', 'participants.user', 'creator']);
            $activeParticipant = $activeConversation->participants->firstWhere('user_id', $user->id);
            $initialMessages = $activeConversation->messages()
                ->with('user')
                ->latest('id')
                ->limit(80)
                ->get()
                ->reverse()
                ->values();

            $lastMessageId = $initialMessages->max('id');
            if ($lastMessageId && $lastMessageId > ($activeParticipant?->last_read_message_id ?? 0)) {
                $activeParticipant?->update(['last_read_message_id' => $lastMessageId]);
                $activeParticipant?->setAttribute('last_read_message_id', $lastMessageId);
            }
            $this->markChatNotificationsAsRead($user, $activeConversation);
        }

        $conversations = $this->conversationList($user);
        if ($activeConversation) {
            $conversations->firstWhere('id', $activeConversation->id)?->setAttribute('unread_count', 0);
        }

        $contacts = User::query()
            ->whereIn('role', self::CHAT_ROLES)
            ->where('id', '!=', $user->id)
            ->orderByRaw("CASE role WHEN 'ketua' THEN 1 WHEN 'pengurus' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->get();

        return view('chat.index', compact(
            'activeConversation',
            'activeParticipant',
            'contacts',
            'conversations',
            'initialMessages',
            'user'
        ));
    }

    /** @return Collection<int, ChatConversation> */
    private function conversationList(User $user): Collection
    {
        $conversations = ChatConversation::query()
            ->whereHas('participants', fn ($query) => $query->where('user_id', $user->id))
            ->with(['users', 'participants', 'latestMessage.user'])
            ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
            ->get();

        $unread = ChatMessage::query()
            ->select('chat_messages.conversation_id')
            ->selectRaw('COUNT(*) as aggregate')
            ->join('chat_participants as reader', function ($join) use ($user): void {
                $join->on('reader.conversation_id', '=', 'chat_messages.conversation_id')
                    ->where('reader.user_id', '=', $user->id);
            })
            ->where(function ($query) use ($user): void {
                $query->whereNull('chat_messages.user_id')
                    ->orWhere('chat_messages.user_id', '!=', $user->id);
            })
            ->whereRaw('chat_messages.id > COALESCE(reader.last_read_message_id, 0)')
            ->groupBy('chat_messages.conversation_id')
            ->pluck('aggregate', 'chat_messages.conversation_id');

        return $conversations->each(function (ChatConversation $conversation) use ($unread): void {
            $conversation->setAttribute('unread_count', (int) ($unread[$conversation->id] ?? 0));
        });
    }

    private function ensureParticipant(ChatConversation $conversation, User $user): ChatParticipant
    {
        $participant = $conversation->participants()->where('user_id', $user->id)->first();

        abort_unless($participant, 404);

        return $participant;
    }

    private function ensureGroupOwner(ChatConversation $conversation, User $user): ChatParticipant
    {
        abort_unless($conversation->isGroup(), 422, 'Percakapan ini bukan grup.');

        $participant = $this->ensureParticipant($conversation, $user);
        abort_unless($participant->role === 'owner', 403, 'Hanya pembuat grup yang dapat mengelola anggota.');

        return $participant;
    }

    private function markChatNotificationsAsRead(User $user, ChatConversation $conversation): void
    {
        $user->unreadNotifications()
            ->where('data->category', 'chat')
            ->where('data->conversation_id', $conversation->id)
            ->update(['read_at' => now()]);
    }

    /** @return array<string, mixed> */
    private function messagePayload(ChatMessage $message, User $viewer): array
    {
        return [
            'id' => $message->id,
            'body' => $message->body,
            'user_id' => $message->user_id,
            'user_name' => $message->user?->name ?? 'Akun dihapus',
            'user_initial' => $message->user?->initial ?? '?',
            'user_avatar' => $message->user?->foto_url,
            'role_label' => $message->user?->role_label ?? 'Tidak tersedia',
            'is_mine' => $message->user_id === $viewer->id,
            'time' => $message->created_at->format('H:i'),
            'sent_at' => $message->created_at->toIso8601String(),
        ];
    }
}
