<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $isOnline;

    public function __construct(User $user, bool $isOnline)
    {
        $this->user = $user;
        $this->isOnline = $isOnline;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-status');
    }

    public function broadcastAs()
    {
        return 'UserStatusChanged';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'is_online' => $this->isOnline,
            'last_seen' => $this->user->lastSeen()
        ];
    }
}
