<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollingVote extends Model
{
    protected $table = 'polling_vote';
    protected $fillable = ['polling_id', 'user_id', 'pilihan'];

    public function polling()
    {
        return $this->belongsTo(Polling::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
