<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\MessageEncryptionService;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at', 'read_at'];

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'read_at'
    ];

    protected $hidden = [
        'content'
    ];

    protected $appends = [
        'decrypted_content'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    protected $with = ['attachments'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            $encryptionService = app(MessageEncryptionService::class);
            $message->content = $encryptionService->encrypt($message->content);
        });

        static::deleting(function ($message) {
            // Delete all attachments when message is deleted
            foreach ($message->attachments as $attachment) {
                Storage::delete($attachment->path);
                $attachment->delete();
            }
        });
    }

    public function getDecryptedContentAttribute()
    {
        $encryptionService = app(MessageEncryptionService::class);
        return $encryptionService->decrypt($this->content);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($query) use ($user1Id, $user2Id) {
            $query->where(function ($q) use ($user1Id, $user2Id) {
                $q->where('sender_id', $user1Id)
                    ->where('recipient_id', $user2Id);
            })->orWhere(function ($q) use ($user1Id, $user2Id) {
                $q->where('sender_id', $user2Id)
                    ->where('recipient_id', $user1Id);
            });
        });
    }

    public function canAccess(User $user): bool
    {
        return $user->id === $this->sender_id || $user->id === $this->recipient_id;
    }
}
