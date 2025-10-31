<?php

namespace App\Models;

use App\Events\NotificationCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Notification extends DatabaseNotification
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'data',
        'read_at',
        'archived_at',
        'notifiable_type',
        'notifiable_id',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'created' => NotificationCreated::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            // If notifiable fields are set but user_id is not, populate user_id
            if (empty($model->user_id) && !empty($model->notifiable_id) && $model->notifiable_type === User::class) {
                $model->user_id = $model->notifiable_id;
            }

            // If user_id is set but notifiable fields are not, populate notifiable fields
            if (!empty($model->user_id) && (empty($model->notifiable_type) || empty($model->notifiable_id))) {
                $model->notifiable_type = User::class;
                $model->notifiable_id = $model->user_id;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include unarchived notifications.
     */
    public function scopeUnarchived(Builder $query): void
    {
        $query->whereNull('archived_at');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        if ($this->read_at) {
            $this->read_at = null;
            $this->save();
        }
    }

    /**
     * Archive the notification.
     */
    public function archive(): void
    {
        if (! $this->archived_at) {
            $this->archived_at = now();
            $this->save();
        }
    }

    /**
     * Unarchive the notification.
     */
    public function unarchive(): void
    {
        if ($this->archived_at) {
            $this->archived_at = null;
            $this->save();
        }
    }
}
