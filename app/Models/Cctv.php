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

    public const CONNECTION_WIRED = 'wired';

    public const CONNECTION_WIRELESS = 'wireless';

    protected $fillable = [
        'name',
        'building_id',
        'room_id',
        'ip_rtsp',
        'port',
        'connection_type',
        'status',
        'last_seen_at',
        'stream_username',
        'stream_password',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'port' => 'integer',
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
     * Get the full RTSP URL with authentication
     */
    public function getFullRtspUrlAttribute(): string
    {
        // If we already have a complete RTSP URL with credentials, return it
        if (str_contains($this->ip_rtsp, '://') && str_contains($this->ip_rtsp, '@')) {
            return $this->ip_rtsp;
        }

        // If we have username/password, add them to the URL
        if ($this->stream_username && $this->stream_password) {
            $parsedUrl = parse_url($this->ip_rtsp);
            if ($parsedUrl) {
                $scheme = $parsedUrl['scheme'] ?? 'rtsp';
                $host = $parsedUrl['host'] ?? $this->ip_rtsp;
                $port = isset($parsedUrl['port']) ? ':'.$parsedUrl['port'] : ':554';
                $path = $parsedUrl['path'] ?? '/streaming/channels/101';
                $query = isset($parsedUrl['query']) ? '?'.$parsedUrl['query'] : '';

                // If no scheme was in the original URL, add it
                if (! str_contains($this->ip_rtsp, '://')) {
                    return "rtsp://{$this->stream_username}:{$this->stream_password}@{$host}{$port}{$path}{$query}";
                }

                return "{$scheme}://{$this->stream_username}:{$this->stream_password}@{$host}{$port}{$path}{$query}";
            }
        }

        // Return the original URL if no credentials are available
        return $this->ip_rtsp;
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
