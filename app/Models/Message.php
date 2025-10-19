<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'subject',
        'message',
        'body',
        'read_at',
        'priority',
        'type',
        'delivered_at',
        'last_typing_at',
        'archived_at',
        'reply_to_message_id',
        'forwarded_from_user_id',
        'is_edited',
        'edited_at',
        'message_type', // text, image, file, voice, etc.
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'reaction',
        'is_pinned',
        'pinned_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'last_typing_at' => 'datetime',
        'archived_at' => 'datetime',
        'edited_at' => 'datetime',
        'pinned_at' => 'datetime',
        'is_edited' => 'boolean',
        'is_pinned' => 'boolean',
        'attachment_size' => 'integer',
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Scope a query to only include messages between two users.
     */
    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user1Id)
              ->where('to_user_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('from_user_id', $user2Id)
              ->where('to_user_id', $user1Id);
        });
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Mark the message as delivered.
     */
    public function markAsDelivered()
    {
        if (!$this->delivered_at) {
            $this->delivered_at = now();
            $this->save();
        }
    }

    /**
     * Check if the message has been read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Check if the message has been delivered.
     */
    public function isDelivered(): bool
    {
        return $this->delivered_at !== null;
    }

    /**
     * Accessor for read status.
     */
    public function getIsReadAttribute(): bool
    {
        return $this->isRead();
    }

    /**
     * Accessor for delivered status.
     */
    public function getIsDeliveredAttribute(): bool
    {
        return $this->isDelivered();
    }

    /**
     * Get the message that this message is replying to.
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    /**
     * Get the user who forwarded this message.
     */
    public function forwardedFromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forwarded_from_user_id');
    }

    /**
     * Check if the message has been edited.
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }

    /**
     * Check if the message is pinned.
     */
    public function isPinned(): bool
    {
        return $this->is_pinned;
    }

    /**
     * Check if the message has an attachment.
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Get message status for display (like WhatsApp).
     */
    public function getStatusAttribute(): string
    {
        if ($this->isRead()) {
            return 'read';
        } elseif ($this->isDelivered()) {
            return 'delivered';
        } else {
            return 'sent';
        }
    }

    /**
     * Get formatted attachment size.
     */
    public function getFormattedAttachmentSizeAttribute(): string
    {
        if (!$this->attachment_size) {
            return '';
        }

        $bytes = $this->attachment_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope to get unread messages for a user.
     */
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->where('to_user_id', $userId)
                    ->whereNull('read_at');
    }

    /**
     * Scope to get messages between two users with pagination.
     */
    public function scopeConversationBetween($query, $user1Id, $user2Id, $limit = 50)
    {
        return $query->betweenUsers($user1Id, $user2Id)
                    ->with(['fromUser:id,name', 'toUser:id,name', 'replyTo'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope to get recent conversations for a user.
     */
    public function scopeRecentConversations($query, $userId)
    {
        return $query->selectRaw('
                CASE 
                    WHEN from_user_id = ? THEN to_user_id 
                    ELSE from_user_id 
                END as other_user_id,
                MAX(created_at) as last_message_at,
                COUNT(*) as message_count,
                SUM(CASE WHEN to_user_id = ? AND read_at IS NULL THEN 1 ELSE 0 END) as unread_count
            ', [$userId, $userId])
            ->where(function($q) use ($userId) {
                $q->where('from_user_id', $userId)
                  ->orWhere('to_user_id', $userId);
            })
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc');
    }

    /**
     * Mark message as edited.
     */
    public function markAsEdited()
    {
        $this->is_edited = true;
        $this->edited_at = now();
        $this->save();
    }

    /**
     * Pin/unpin message.
     */
    public function togglePin()
    {
        $this->is_pinned = !$this->is_pinned;
        $this->pinned_at = $this->is_pinned ? now() : null;
        $this->save();
    }

    /**
     * Add reaction to message.
     */
    public function addReaction($reaction)
    {
        $this->reaction = $reaction;
        $this->save();
    }

    /**
     * Remove reaction from message.
     */
    public function removeReaction()
    {
        $this->reaction = null;
        $this->save();
    }
}
