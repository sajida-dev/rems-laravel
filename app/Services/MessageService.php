<?php

namespace App\Services;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use App\Events\NewMessage;

class MessageService
{
    public function sendMessage(User $sender, User $recipient, string $content)
    {
        // Create message
        $message = Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'content' => $content,
        ]);

        // Load relationships for real-time event
        $message->load(['sender', 'recipient']);

        // Store in Redis for real-time access
        $this->storeMessageInRedis($message);

        // Broadcast new message event
        broadcast(new NewMessage($message))->toOthers();

        // Notify recipient
        $recipient->notify(new \App\Notifications\RealTimeNotification(
            title: 'New Message',
            message: "New message from {$sender->name}",
            type: 'message',
            link: route('messages.show', $recipient->id),
            data: [
                'message_id' => $message->id,
                'sender_id' => $sender->id,
                'content' => $content
            ]
        ));

        return $message;
    }

    public function getMessages(User $user1, User $user2, int $limit = 50)
    {
        $cacheKey = "messages:{$user1->id}:{$user2->id}";

        // Try to get from Redis first
        $messages = $this->getMessagesFromRedis($user1->id, $user2->id, $limit);

        if (empty($messages)) {
            // If not in Redis, get from database
            $messages = Message::where(function ($query) use ($user1, $user2) {
                $query->where(function ($q) use ($user1, $user2) {
                    $q->where('sender_id', $user1->id)
                        ->where('recipient_id', $user2->id);
                })->orWhere(function ($q) use ($user1, $user2) {
                    $q->where('sender_id', $user2->id)
                        ->where('recipient_id', $user1->id);
                });
            })
                ->with(['sender', 'recipient'])
                ->latest()
                ->take($limit)
                ->get()
                ->reverse()
                ->values();

            // Store in Redis for future requests
            $this->storeMessagesInRedis($user1->id, $user2->id, $messages);
        }

        return $messages;
    }

    private function storeMessageInRedis(Message $message)
    {
        $key = $this->getMessageKey($message->sender_id, $message->recipient_id);
        Redis::rpush($key, json_encode($message));

        // Keep only last 100 messages in Redis
        Redis::ltrim($key, -100, -1);
    }

    private function getMessagesFromRedis(int $user1Id, int $user2Id, int $limit)
    {
        $key = $this->getMessageKey($user1Id, $user2Id);
        $messages = Redis::lrange($key, -$limit, -1);

        return collect($messages)->map(function ($message) {
            return json_decode($message, true);
        })->values();
    }

    private function storeMessagesInRedis(int $user1Id, int $user2Id, $messages)
    {
        $key = $this->getMessageKey($user1Id, $user2Id);
        Redis::del($key);

        foreach ($messages as $message) {
            Redis::rpush($key, json_encode($message));
        }
    }

    private function getMessageKey(int $user1Id, int $user2Id): string
    {
        // Always use the same key regardless of sender/recipient order
        return "messages:" . min($user1Id, $user2Id) . ":" . max($user1Id, $user2Id);
    }
}
