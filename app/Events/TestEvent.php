<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct($message)
    {
        $this->notification = [
            'id' => uniqid(),
            'type' => 'test',
            'message' => $message,
            'read_at' => null,
            'created_at' => now()->toDateTimeString(),
            'data' => [
                'title' => 'Test Notification',
                'message' => $message,
                'icon' => 'fa-bell'
            ]
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . auth()->id());
    }

    public function broadcastAs()
    {
        return 'RealTimeNotification';
    }
}
