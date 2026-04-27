<?php
namespace App\Events;
 
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
 
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;
 
    public function __construct(public Message $message) {}
 
    // canal privé entre les deux utilisateurs
    //  private-chat.3.7 
    public function broadcastOn(): array
    {
        $ids = collect([
            $this->message->sender_id,
            $this->message->receiver_id
        ])->sort()->values();
 
        return [
            new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1]),
        ];
    }
 
    // ce qui est envoyé au frontend
    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'body'        => $this->message->body,
            'sender_id'   => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'is_read'     => $this->message->is_read,
            'created_at'  => $this->message->created_at->format('H:i'),
            'sender_name' => $this->message->sender->name,
        ];
    }
 
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}