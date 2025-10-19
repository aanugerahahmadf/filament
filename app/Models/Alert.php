<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Alert extends Model
{
    use HasFactory;

    public const SEVERITY_CRITICAL = 'critical';

    public const SEVERITY_HIGH = 'high';

    public const SEVERITY_MEDIUM = 'medium';

    public const SEVERITY_LOW = 'low';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_ACKNOWLEDGED = 'acknowledged';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_SUPPRESSED = 'suppressed';

    protected $fillable = [
        'alertable_type',
        'alertable_id',
        'user_id',
        'title',
        'message',
        'severity',
        'status',
        'category',
        'source',
        'triggered_at',
        'acknowledged_at',
        'resolved_at',
        'suppressed_at',
        'data',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'suppressed_at' => 'datetime',
        'data' => 'array',
    ];

    public function alertable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active alerts
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Scope for acknowledged alerts
    public function scopeAcknowledged($query)
    {
        return $query->where('status', self::STATUS_ACKNOWLEDGED);
    }

    // Scope for resolved alerts
    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    // Scope for suppressed alerts
    public function scopeSuppressed($query)
    {
        return $query->where('status', self::STATUS_SUPPRESSED);
    }

    // Scope for alerts by severity
    public function scopeOfSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    // Scope for alerts by category
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Check if alert is active
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Check if alert is acknowledged
    public function isAcknowledged(): bool
    {
        return $this->status === self::STATUS_ACKNOWLEDGED;
    }

    // Check if alert is resolved
    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    // Check if alert is suppressed
    public function isSuppressed(): bool
    {
        return $this->status === self::STATUS_SUPPRESSED;
    }

    // Acknowledge the alert
    public function acknowledge(?User $user = null): void
    {
        if (! $this->isAcknowledged()) {
            $this->update([
                'status' => self::STATUS_ACKNOWLEDGED,
                'acknowledged_at' => now(),
                'user_id' => $user?->id,
            ]);
        }
    }

    // Resolve the alert
    public function resolve(?User $user = null): void
    {
        if (! $this->isResolved()) {
            $this->update([
                'status' => self::STATUS_RESOLVED,
                'resolved_at' => now(),
                'user_id' => $user?->id,
            ]);
        }
    }

    // Suppress the alert
    public function suppress(?User $user = null): void
    {
        if (! $this->isSuppressed()) {
            $this->update([
                'status' => self::STATUS_SUPPRESSED,
                'suppressed_at' => now(),
                'user_id' => $user?->id,
            ]);
        }
    }

    // Get severity badge class
    public function getSeverityBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            self::SEVERITY_CRITICAL => 'danger',
            self::SEVERITY_HIGH => 'warning',
            self::SEVERITY_MEDIUM => 'info',
            self::SEVERITY_LOW => 'secondary',
            default => 'secondary',
        };
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'danger',
            self::STATUS_ACKNOWLEDGED => 'warning',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_SUPPRESSED => 'secondary',
            default => 'secondary',
        };
    }

    // Get category badge class
    public function getCategoryBadgeClassAttribute(): string
    {
        return match ($this->category) {
            'system' => 'primary',
            'security' => 'danger',
            'network' => 'info',
            'hardware' => 'warning',
            'software' => 'success',
            default => 'secondary',
        };
    }
}
