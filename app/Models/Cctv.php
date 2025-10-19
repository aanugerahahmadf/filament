<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cctv extends Model
{
    use HasFactory;

    public const STATUS_ONLINE = 'online';

    public const STATUS_OFFLINE = 'offline';

    public const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'building_id',
        'room_id',
        'name',
        'ip_rtsp',
        'status',
        'latitude',
        'longitude',
        'hls_path',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope a query to only include online CCTVs.
     */
    public function scopeOnline($query)
    {
        return $query->where('status', self::STATUS_ONLINE);
    }

    /**
     * Scope a query to only include offline CCTVs.
     */
    public function scopeOffline($query)
    {
        return $query->where('status', self::STATUS_OFFLINE);
    }

    /**
     * Scope a query to only include CCTVs in maintenance.
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Get the badge class for the status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ONLINE => 'bg-green-500',
            self::STATUS_OFFLINE => 'bg-red-500',
            self::STATUS_MAINTENANCE => 'bg-yellow-500',
            default => 'bg-gray-500',
        };
    }
}
