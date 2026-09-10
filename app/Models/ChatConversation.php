<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatConversation extends Model
{
    use HasFactory;

    public const TYPE_PRIVATE = 'private';

    public const TYPE_GROUP = 'group';

    protected $fillable = [
        'type',
        'name',
        'private_key',
        'created_by',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'conversation_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'last_read_message_id'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany('id');
    }

    public function isGroup(): bool
    {
        return $this->type === self::TYPE_GROUP;
    }

    public function otherUser(User $viewer): ?User
    {
        $users = $this->relationLoaded('users') ? $this->users : $this->users()->get();

        return $users->first(fn (User $user): bool => $user->id !== $viewer->id);
    }

    public function displayName(User $viewer): string
    {
        if ($this->isGroup()) {
            return $this->name ?: 'Grup tanpa nama';
        }

        return $this->otherUser($viewer)?->name ?? 'Akun tidak tersedia';
    }
}
