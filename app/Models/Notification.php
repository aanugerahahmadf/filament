<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Notification extends Model
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
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
