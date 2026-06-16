<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Ai\Models\ConversationMessage;

class Message extends ConversationMessage
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function excerpt(int $length = 100): string
    {
        $content = $this->content ?? '';

        return mb_strlen($content) > $length
            ? mb_substr($content, 0, $length).'…'
            : $content;
    }
}
