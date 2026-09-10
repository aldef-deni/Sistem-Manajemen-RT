<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function unreadCountFor(User $user): int
    {
        return static::query()
            ->join('chat_participants as reader', function ($join) use ($user): void {
                $join->on('reader.conversation_id', '=', 'chat_messages.conversation_id')
                    ->where('reader.user_id', '=', $user->id);
            })
            ->where(function ($query) use ($user): void {
                $query->whereNull('chat_messages.user_id')
                    ->orWhere('chat_messages.user_id', '!=', $user->id);
            })
            ->whereRaw('chat_messages.id > COALESCE(reader.last_read_message_id, 0)')
            ->count();
    }
}
