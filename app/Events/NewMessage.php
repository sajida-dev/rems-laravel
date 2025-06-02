<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'recipient']);
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('messages.' . $this->message->recipient_id),
            new PrivateChannel('messages.' . $this->message->sender_id)
        ];
    }

    public function broadcastAs()
    {
        return 'NewMessage';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'recipient_id' => $this->message->recipient_id,
                'content' => $this->message->decrypted_content,
                'created_at' => $this->message->created_at,
                'read_at' => $this->message->read_at,
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                    'profile_photo_path' => $this->message->sender->profile_photo_path
                ],
                'recipient' => [
                    'id' => $this->message->recipient->id,
                    'name' => $this->message->recipient->name,
                    'profile_photo_path' => $this->message->recipient->profile_photo_path
                ],
                'unread_count' => $this->message->recipient->unreadMessages()->count()
            ]
        ];
    }
}
