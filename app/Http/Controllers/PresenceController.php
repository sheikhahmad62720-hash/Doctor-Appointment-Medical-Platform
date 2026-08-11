<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function heartbeat(Request $request)
    {
        $request->user()->forceFill(['last_seen_at' => now()])->save();

        return response()->json(['ok' => true]);
    }

    public function peerStatus(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless(
            $user->id === $conversation->patient_id || $user->id === $conversation->admin_id,
            403,
        );

        $peerId = $user->id === $conversation->patient_id ? $conversation->admin_id : $conversation->patient_id;
        $peer = User::find($peerId);

        return response()->json([
            'last_seen_at' => $peer?->last_seen_at?->toISOString(),
            'name' => $peer?->name,
        ]);
    }
}
