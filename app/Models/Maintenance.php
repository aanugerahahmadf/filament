<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'cctv_id',
        'technician_id',
        'scheduled_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'status',
        'type',
        'description',
        'notes',
        'cost',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function cctv(): BelongsTo
    {
        return $this->belongsTo(Cctv::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Scope for scheduled maintenance
    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    // Scope for in-progress maintenance
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    // Scope for completed maintenance
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Scope for cancelled maintenance
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Check if maintenance is scheduled
    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    // Check if maintenance is in progress
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    // Check if maintenance is completed
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Check if maintenance is cancelled
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_SCHEDULED => 'info',
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary',
        };
    }

    // Get status badge text
    public function getStatusBadgeTextAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    // Get type badge class
    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'preventive' => 'primary',
            'corrective' => 'danger',
            'emergency' => 'warning',
            default => 'secondary',
        };
    }
}
