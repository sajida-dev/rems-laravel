<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\NewMessage;
use App\Services\NotificationService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        // Get all users the current user has chatted with
        $chats = User::whereHas('sentMessages', function ($query) {
            $query->where('recipient_id', Auth::id());
        })->orWhereHas('receivedMessages', function ($query) {
            $query->where('sender_id', Auth::id());
        })
            ->with(['sentMessages' => function ($query) {
                $query->where('recipient_id', Auth::id())
                    ->latest()
                    ->limit(1);
            }, 'receivedMessages' => function ($query) {
                $query->where('sender_id', Auth::id())
                    ->latest()
                    ->limit(1);
            }])
            ->get()
            ->map(function ($user) {
                // Get the latest message between the two users
                $lastMessage = Message::where(function ($query) use ($user) {
                    $query->where('sender_id', Auth::id())
                        ->where('recipient_id', $user->id);
                })->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('recipient_id', Auth::id());
                })
                    ->latest()
                    ->first();

                $unreadCount = Message::where('sender_id', $user->id)
                    ->where('recipient_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                    'is_online' => $user->isOnline(),
                    'last_seen' => $user->lastSeen(),
                    'last_message' => $lastMessage ? [
                        'content' => $lastMessage->decrypted_content ?? $lastMessage->content,
                        'created_at' => $lastMessage->created_at,
                        'sender_id' => $lastMessage->sender_id,
                        'is_sent_by_me' => $lastMessage->sender_id === Auth::id(),
                        'read_at' => $lastMessage->read_at
                    ] : null,
                    'unread_count' => $unreadCount
                ];
            })
            ->sortByDesc(function ($chat) {
                return $chat['last_message']['created_at'] ?? null;
            })
            ->values();

        return Inertia::render('Messages/Index', [
            'user' => Auth::user(),
            'initialChats' => $chats
        ]);
    }

    public function show(User $user)
    {
        // Check if user is authorized to view messages
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot chat with yourself'], 403);
        }

        $messages = Message::betweenUsers(Auth::id(), $user->id)
            ->with(['sender', 'recipient', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        // Mark messages as read
        Message::where('recipient_id', Auth::id())
            ->where('sender_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $formattedMessages = $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'recipient_id' => $message->recipient_id,
                'content' => $message->decrypted_content,
                'created_at' => $message->created_at,
                'read_at' => $message->read_at,
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                    'profile_photo_path' => $message->sender->profile_photo_path
                ],
                'recipient' => [
                    'id' => $message->recipient->id,
                    'name' => $message->recipient->name,
                    'profile_photo_path' => $message->recipient->profile_photo_path
                ],
                'attachments' => $message->attachments
            ];
        });

        return response()->json([
            'messages' => $formattedMessages
        ]);
    }

    public function store(Request $request, User $user)
    {
        // Validate user is not sending to themselves
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot send message to yourself'], 403);
        }

        $validated = $request->validate([
            'content' => 'nullable|string|max:5000',
            'attachments.*' => 'nullable|file|max:10240' // 10MB max per file
        ]);

        DB::beginTransaction();

        try {
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $user->id,
                'content' => $validated['content'] ?? ''
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('message-attachments');

                    $message->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            // Load relationships immediately
            $message->load(['sender', 'recipient', 'attachments']);

            // Broadcast new message event
            broadcast(new NewMessage($message))->toOthers();

            DB::commit();

            return response()->json([
                'message' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'recipient_id' => $message->recipient_id,
                    'content' => $message->decrypted_content,
                    'created_at' => $message->created_at,
                    'read_at' => $message->read_at,
                    'sender' => $message->sender,
                    'recipient' => $message->recipient,
                    'attachments' => $message->attachments->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'name' => $attachment->filename,
                            'type' => $attachment->mime_type,
                            'size' => $attachment->size,
                            'url' => Storage::url($attachment->path)
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        // Delete attachments
        foreach ($message->attachments as $attachment) {
            Storage::delete($attachment->path);
            $attachment->delete();
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('update', $message);
        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    public function storeAttachment(Request $request, Message $message)
    {
        $this->authorize('update', $message);

        $validated = $request->validate([
            'attachment' => 'required|file|max:10240' // 10MB max
        ]);

        $file = $request->file('attachment');
        $path = $file->store('message-attachments');

        $attachment = $message->attachments()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);

        return response()->json([
            'attachment' => $attachment
        ]);
    }

    public function destroyAttachment(Message $message, MessageAttachment $attachment)
    {
        $this->authorize('update', $message);

        Storage::delete($attachment->path);
        $attachment->delete();

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:3'
        ]);

        $messages = Message::where(function ($query) use ($validated) {
            $query->where('sender_id', Auth::id())
                ->orWhere('recipient_id', Auth::id());
        })
            ->where('content', 'like', '%' . $validated['query'] . '%')
            ->with(['sender', 'recipient'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function notifications()
    {
        // Get all unread messages grouped by sender
        $unreadMessages = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->with(['sender'])
            ->get()
            ->groupBy('sender_id')
            ->map(function ($messages, $senderId) {
                $sender = $messages->first()->sender;
                return [
                    'id' => $sender->id,
                    'name' => $sender->name,
                    'profile_photo_path' => $sender->profile_photo_path,
                    'last_message' => [
                        'content' => $messages->last()->decrypted_content,
                        'created_at' => $messages->last()->created_at
                    ],
                    'unread_count' => $messages->count(),
                    'type' => 'message',
                    'link' => route('messages.index', ['user' => $sender->id])
                ];
            })
            ->values();

        return response()->json([
            'messages' => $unreadMessages,
            'total_unread' => $unreadMessages->sum('unread_count')
        ]);
    }

    public function markAllAsRead()
    {
        Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function searchUsers(Request $request)
    {
        try {
            $validated = $request->validate([
                'query' => 'required|string|min:2'
            ]);
            $users = User::where('id', '!=', Auth::id())
                ->where(function ($query) use ($validated) {
                    $query->where('name', 'like', '%' . $validated['query'] . '%')
                        ->orWhere('email', 'like', '%' . $validated['query'] . '%');
                })
                ->select('id', 'name', 'email', 'profile_photo_path')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markChatAsRead(User $user)
    {
        // Mark all unread messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function getLatestChats()
    {
        // Get all users the current user has chatted with
        $chats = User::whereHas('sentMessages', function ($query) {
            $query->where('recipient_id', Auth::id());
        })->orWhereHas('receivedMessages', function ($query) {
            $query->where('sender_id', Auth::id());
        })
            ->with(['sentMessages' => function ($query) {
                $query->where('recipient_id', Auth::id())
                    ->latest()
                    ->limit(1);
            }, 'receivedMessages' => function ($query) {
                $query->where('sender_id', Auth::id())
                    ->latest()
                    ->limit(1);
            }])
            ->get()
            ->map(function ($user) {
                // Get the latest message between the two users
                $lastMessage = Message::where(function ($query) use ($user) {
                    $query->where('sender_id', Auth::id())
                        ->where('recipient_id', $user->id);
                })->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('recipient_id', Auth::id());
                })
                    ->latest()
                    ->first();

                // Get unread count for this specific chat
                $unreadCount = Message::where('sender_id', $user->id)
                    ->where('recipient_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                    'is_online' => $user->isOnline(),
                    'last_seen' => $user->lastSeen(),
                    'last_message' => $lastMessage ? [
                        'content' => $lastMessage->decrypted_content ?? $lastMessage->content,
                        'created_at' => $lastMessage->created_at,
                        'sender_id' => $lastMessage->sender_id,
                        'is_sent_by_me' => $lastMessage->sender_id === Auth::id(),
                        'read_at' => $lastMessage->read_at
                    ] : null,
                    'unread_count' => $unreadCount
                ];
            })
            ->sortByDesc(function ($chat) {
                return $chat['last_message']['created_at'] ?? null;
            })
            ->values();

        return response()->json([
            'chats' => $chats,
            'total_unread' => Message::where('recipient_id', Auth::id())
                ->whereNull('read_at')
                ->count()
        ]);
    }
}
