<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with('sender', 'receiver')
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(fn($messages) => $messages->first());

        $unreadCount = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('client.messages.index', compact('conversations', 'unreadCount'));
    }

    public function show(User $user)
    {
        $myId = auth()->id();

        $messages = Message::where(function ($q) use ($myId, $user) {
                $q->where('sender_id', $myId)
                  ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($myId, $user) {
                $q->where('sender_id', $user->id)
                  ->where('receiver_id', $myId);
            })
            ->orderBy('created_at')
            ->get();

        // Marquer les messages reçus comme lus
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $myId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('client.messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        return redirect()->route('client.messages.show', $user);
    }
}