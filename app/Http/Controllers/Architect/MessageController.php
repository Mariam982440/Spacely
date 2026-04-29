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
    public function show(User $user)
    {
        $myId = auth()->id();
 
        $messages = Message::where(function ($q) use ($myId, $user) {
                $q->where('sender_id', $myId)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($myId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at')
            ->get();
 
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $myId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
 
        return view('architect.messages.show', compact('messages', 'user'));
    }
    public function store(StoreMessageRequest $request, User $user)
    {
        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);
 
        $message->load('sender');
 
        // diffuser le message en temps réel via Reverb
        broadcast(new MessageSent($message))->toOthers();
 
        // si requête AJAX (fetch depuis JS) - retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'id'         => $message->id,
                'body'       => $message->body,
                'sender_id'  => $message->sender_id,
                'created_at' => $message->created_at->format('H:i'),
            ]);
        }
 
        return back();
    }
}