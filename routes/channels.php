<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversation}', function ($user, $conversation) {
    $conversation = Conversation::find($conversation);

    if (! $conversation) {
        return false;
    }

    return $user->id === $conversation->patient_id || $user->id === $conversation->admin_id;
});

Broadcast::channel('online', function ($user) {
    return $user ? [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ] : null;
});
