<?php

namespace App\Services;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class MessageService
{
    public function sendMessage(User $sender, User $recipient, string $content, ?int $conversationId = null)
    {
        // Get or create conversation
        $conversation = $conversationId
            ? Conversation::findOrFail($conversationId)
            : $this->getOrCreateConversation($sender, $recipient);

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => $content,
        ]);

        // Store in Redis for real-time access
        $this->storeMessageInRedis($message);

        // Notify recipient
        $recipient->notify(new \App\Notifications\RealTimeNotification(
            title: 'New Message',
            message: "New message from {$sender->name}",
            type: 'message',
            link: route('messages.show', $conversation->id),
            data: [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'content' => $content
            ]
        ));

        return $message;
    }

    public function getMessages(int $conversationId, int $limit = 50)
    {
        // Try to get from Redis first
        $messages = $this->getMessagesFromRedis($conversationId, $limit);

        if (empty($messages)) {
            // If not in Redis, get from database
            $messages = Message::where('conversation_id', $conversationId)
                ->with('sender')
                ->latest()
                ->take($limit)
                ->get()
                ->reverse()
                ->values();

            // Store in Redis for future requests
            $this->storeMessagesInRedis($conversationId, $messages);
        }

        return $messages;
    }

    private function getOrCreateConversation(User $user1, User $user2)
    {
        return Conversation::firstOrCreate(
            ['user1_id' => min($user1->id, $user2->id), 'user2_id' => max($user1->id, $user2->id)],
            ['status' => 'active']
        );
    }

    private function storeMessageInRedis(Message $message)
    {
        $key = "conversation:{$message->conversation_id}:messages";
        Redis::rpush($key, json_encode($message->load('sender')));

        // Keep only last 100 messages in Redis
        Redis::ltrim($key, -100, -1);
    }

    private function getMessagesFromRedis(int $conversationId, int $limit)
    {
        $key = "conversation:{$conversationId}:messages";
        $messages = Redis::lrange($key, -$limit, -1);

        return collect($messages)->map(function ($message) {
            return json_decode($message, true);
        })->values();
    }

    private function storeMessagesInRedis(int $conversationId, $messages)
    {
        $key = "conversation:{$conversationId}:messages";
        Redis::del($key);

        foreach ($messages as $message) {
            Redis::rpush($key, json_encode($message));
        }
    }
}
