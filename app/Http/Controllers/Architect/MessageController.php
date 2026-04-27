<?php
namespace App\Http\Controllers\Architect;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\Architect\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
 
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
 
        return view('architect.messages.index', compact('conversations', 'unreadCount'));
    }
}