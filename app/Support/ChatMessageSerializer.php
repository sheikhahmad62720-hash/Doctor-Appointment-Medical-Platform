<?php

namespace App\Support;

use App\Models\Message;

class ChatMessageSerializer
{
    public static function serialize(Message $m, ?string $senderName = null): array
    {
        $deleted = $m->deleted_at !== null;

        $replyTo = null;
        if ($m->reply_to_id) {
            $rm = Message::find($m->reply_to_id);
            $replyTo = $rm ? [
                'id' => $rm->id,
                'sender_name' => $rm->sender?->name ?? 'User',
                'message' => $rm->deleted_at !== null ? 'This message was deleted' : $rm->message,
            ] : null;
        }

        return [
            'id' => $m->id,
            'conversation_id' => $m->conversation_id,
            'sender_id' => $m->sender_id,
            'sender_name' => $senderName ?? $m->sender?->name ?? 'User',
            'message' => $deleted ? '' : $m->message,
            'type' => $m->type,
            'file_path' => $m->file_path,
            'file_name' => $m->file_name,
            'file_size' => $m->file_size,
            'mime' => $m->mime,
            'file_url' => $m->file_path ? '/storage/'.ltrim($m->file_path, '/') : null,
            'reply_to' => $replyTo,
            'is_read' => (bool) $m->is_read,
            'is_delivered' => (bool) $m->is_delivered,
            'deleted' => $deleted,
            'created_at' => $m->created_at->toISOString(),
            'time_label' => $m->created_at->format('g:i A'),
        ];
    }
}
