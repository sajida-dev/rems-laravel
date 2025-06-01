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
                $lastMessage = $user->sentMessages->first() ?? $user->receivedMessages->first();
                $unreadCount = Message::where('sender_id', $user->id)
                    ->where('recipient_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo_path' => $user->profile_photo_path,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount
                ];
            })
            ->sortByDesc(function ($chat) {
                return $chat['last_message']?->created_at;
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
            ->latest()
            ->paginate(20);

        // Mark messages as read
        Message::where('recipient_id', Auth::id())
            ->where('sender_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'recipient_id' => $message->recipient_id,
                    'content' => $message->decrypted_content,
                    'created_at' => $message->created_at,
                    'read_at' => $message->read_at,
                    'sender' => $message->sender,
                    'recipient' => $message->recipient,
                    'attachments' => $message->attachments
                ];
            })
        ]);
    }

    public function store(Request $request, User $user)
    {
        // Validate user is not sending to themselves
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot send message to yourself'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id,
            'content' => $validated['content']
        ]);

        $message->load(['sender', 'recipient']);

        // Notify recipient
        $this->notificationService->notify(
            $user,
            'message',
            "New message from " . Auth::user()->name,
            [
                'title' => 'New Message',
                'message' => $validated['content'],
                'link' => route('messages.index')
            ]
        );

        // Broadcast new message event
        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'recipient_id' => $message->recipient_id,
                'content' => $message->decrypted_content,
                'created_at' => $message->created_at,
                'read_at' => $message->read_at,
                'sender' => $message->sender,
                'recipient' => $message->recipient
            ]
        ]);
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
        $unreadMessages = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->with(['sender'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'messages' => $unreadMessages
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
            'users' => $users
        ]);
    }
}
