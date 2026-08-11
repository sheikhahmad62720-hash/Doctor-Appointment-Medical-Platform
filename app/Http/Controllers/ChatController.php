<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Support\ChatMessageSerializer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    private function resolveAdmin(): User
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        $user = auth()->user();

        abort_unless(
            $user->id === $conversation->patient_id || $user->id === $conversation->admin_id,
            403,
        );
    }

    private function authorizeMessage(Conversation $conversation, Message $message): void
    {
        abort_unless($message->conversation_id === $conversation->id, 404);

        $this->authorizeConversation($conversation);
    }

    /**
     * Patient's single-window chat page.
     */
    public function patientIndex()
    {
        $patient = auth()->user();
        $admin = $this->resolveAdmin();

        $conversation = Conversation::firstOrCreate(
            ['patient_id' => $patient->id, 'admin_id' => $admin->id],
        );

        $this->markMessagesRead($conversation, exceptSender: $patient->id);

        return Inertia::render('Chat/PatientChat', [
            'conversation' => $this->serializeConversation($conversation),
            'messages' => $this->serializeMessages($this->visibleMessages($conversation)),
            'counterpart' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'initials' => $this->initials($admin->name),
                'last_seen_at' => $admin->last_seen_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Admin's split-screen chat page.
     */
    public function adminIndex()
    {
        $admin = auth()->user();

        $conversations = Conversation::where('admin_id', $admin->id)
            ->with([
                'patient',
                'lastMessage',
            ])
            ->withCount(['unreadMessages' => function ($q) use ($admin) {
                $q->where('is_read', false)->where('sender_id', '!=', $admin->id);
            }])
            ->orderByDesc('last_message_at')
            ->get();

        return Inertia::render('Chat/AdminChat', [
            'conversations' => $conversations->map(fn (Conversation $c) => $this->serializeForAdmin($c)),
        ]);
    }

    public function fetchMessages(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $this->markDeliveredMessages($conversation, exceptSender: auth()->id());

        return response()->json([
            'conversation' => $this->serializeConversation($conversation),
            'messages' => $this->serializeMessages($this->visibleMessages($conversation)),
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'reply_to_id' => ['nullable', 'integer', 'exists:messages,id'],
        ]);

        if (! empty($data['reply_to_id'])) {
            abort_unless(
                Message::where('id', $data['reply_to_id'])->where('conversation_id', $conversation->id)->exists(),
                422,
            );
        }

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $data['message'],
            'type' => 'text',
            'reply_to_id' => $data['reply_to_id'] ?? null,
            'is_read' => false,
            'is_delivered' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message, auth()->user()->name));

        return response()->json([
            'message' => ChatMessageSerializer::serialize($message, auth()->user()->name),
        ]);
    }

    public function attachFile(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $data['file'];
        $type = str_starts_with($file->getMimeType() ?? '', 'image/') ? 'image' : 'file';
        $path = $file->store('chat/'.$conversation->id, 'public');

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => '',
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'is_read' => false,
            'is_delivered' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message, auth()->user()->name));

        return response()->json([
            'message' => ChatMessageSerializer::serialize($message, auth()->user()->name),
        ]);
    }

    public function deleteForMe(Conversation $conversation, Message $message)
    {
        $this->authorizeMessage($conversation, $message);

        $deletedFor = $message->deleted_for ?? [];
        if (! in_array(auth()->id(), $deletedFor)) {
            $deletedFor[] = auth()->id();
            $message->update(['deleted_for' => $deletedFor]);
        }

        return response()->json(['ok' => true]);
    }

    public function deleteForEveryone(Conversation $conversation, Message $message)
    {
        $this->authorizeMessage($conversation, $message);

        abort_unless($message->sender_id === auth()->id() && $message->deleted_at === null, 403);

        $message->update(['deleted_at' => now()]);

        broadcast(new MessageDeleted($message));

        return response()->json(['ok' => true]);
    }

    public function markDelivered(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $this->markDeliveredMessages($conversation, exceptSender: auth()->id());

        return response()->json(['ok' => true]);
    }

    public function markAsRead(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $this->markMessagesRead($conversation, exceptSender: auth()->id());

        return response()->json(['ok' => true]);
    }

    private function visibleMessages(Conversation $conversation)
    {
        return $conversation->messages()
            ->where(function ($q) {
                $q->whereNull('deleted_for')->orWhereJsonDoesntContain('deleted_for', auth()->id());
            })
            ->orderBy('created_at')
            ->get();
    }

    private function markDeliveredMessages(Conversation $conversation, int $exceptSender): void
    {
        $messages = $conversation->messages()
            ->where('is_delivered', false)
            ->where('sender_id', '!=', $exceptSender)
            ->get();

        if ($messages->isEmpty()) {
            return;
        }

        $conversation->messages()
            ->whereIn('id', $messages->pluck('id'))
            ->update(['is_delivered' => true]);

        foreach ($messages as $message) {
            broadcast(new MessageStatusUpdated($message, delivered: true, read: false));
        }
    }

    private function markMessagesRead(Conversation $conversation, int $exceptSender): void
    {
        $messages = $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $exceptSender)
            ->get();

        if ($messages->isEmpty()) {
            return;
        }

        $conversation->messages()
            ->whereIn('id', $messages->pluck('id'))
            ->update(['is_read' => true, 'is_delivered' => true]);

        foreach ($messages as $message) {
            broadcast(new MessageStatusUpdated($message, delivered: true, read: true));
        }
    }

    private function serializeConversation(Conversation $c): array
    {
        return [
            'id' => $c->id,
            'last_message_at' => $c->last_message_at?->toISOString(),
            'patient' => [
                'id' => $c->patient_id,
                'name' => $c->patient?->name,
            ],
            'admin' => [
                'id' => $c->admin_id,
                'name' => $c->admin?->name,
                'last_seen_at' => $c->admin?->last_seen_at?->toISOString(),
            ],
        ];
    }

    private function serializeForAdmin(Conversation $c): array
    {
        $last = $c->lastMessage;

        return [
            'id' => $c->id,
            'last_message_at' => $c->last_message_at?->toISOString(),
            'patient' => [
                'id' => $c->patient_id,
                'name' => $c->patient?->name,
                'email' => $c->patient?->email,
                'initials' => $this->initials($c->patient?->name),
                'last_seen_at' => $c->patient?->last_seen_at?->toISOString(),
            ],
            'last_message' => $last ? [
                'message' => $last->deleted_at !== null ? 'This message was deleted' : $last->message,
                'sender_id' => $last->sender_id,
                'created_at' => $last->created_at->toISOString(),
            ] : null,
            'unread_count' => $c->unread_messages_count,
        ];
    }

    private function serializeMessages($messages): array
    {
        return $messages->map(fn (Message $m) => ChatMessageSerializer::serialize($m))->values()->all();
    }

    private function initials(?string $name): string
    {
        return collect(explode(' ', $name ?? ''))
            ->filter()
            ->slice(0, 2)
            ->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))
            ->join('');
    }
}
