<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recording extends Model
{
    use HasFactory;

    protected $fillable = [
        'cctv_id',
        'filename',
        'filepath',
        'size',
        'duration',
        'started_at',
        'ended_at',
        'format',
        'resolution',
        'status',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'size' => 'integer',
        'duration' => 'integer',
    ];

    public function cctv(): BelongsTo
    {
        return $this->belongsTo(Cctv::class);
    }

    // Scope for active recordings
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for archived recordings
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    // Scope for deleted recordings
    public function scopeDeleted($query)
    {
        return $query->where('status', 'deleted');
    }

    // Check if recording is active
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Check if recording is archived
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    // Check if recording is deleted
    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
    }

    // Get file size in human readable format
    public function getHumanReadableSizeAttribute(): string
    {
        $size = $this->size;

        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2).' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2).' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2).' KB';
        } else {
            return $size.' bytes';
        }
    }

    // Get duration in human readable format
    public function getHumanReadableDurationAttribute(): string
    {
        $duration = $this->duration;

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return sprintf('%02d:%02d', $minutes, $seconds);
        }
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'archived' => 'warning',
            'deleted' => 'danger',
            default => 'secondary',
        };
    }

    // Get status badge text
    public function getStatusBadgeTextAttribute(): string
    {
        return ucfirst($this->status);
    }
}
