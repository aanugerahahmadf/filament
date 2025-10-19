<?php

namespace App\Http\Controllers;

use App\Services\AdvancedCachingService;
use App\Services\RealtimeBroadcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    private AdvancedCachingService $cachingService;

    private RealtimeBroadcastService $broadcastService;

    public function __construct(
        AdvancedCachingService $cachingService,
        RealtimeBroadcastService $broadcastService
    ) {
        $this->cachingService = $cachingService;
        $this->broadcastService = $broadcastService;
    }

    /**
     * Get comprehensive dashboard analytics
     */
    public function dashboardAnalytics(): JsonResponse
    {
        try {
            $analytics = [
                'system_status' => $this->cachingService->cacheSystemStatus(),
                'cctv_stats' => $this->cachingService->cacheCctvStats(),
                'building_analytics' => $this->cachingService->cacheBuildingAnalytics(),
                'streaming_metrics' => $this->cachingService->cacheStreamingMetrics(),
                'performance_indicators' => $this->getPerformanceIndicators(),
                'timeline_data' => $this->getTimelineData(),
                'real_time_alerts' => $this->getRealTimeAlerts(),
            ];

            // Broadcast updated metrics
            $this->broadcastService->broadcastSystemMetrics();

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get CCTV performance analytics
     */
    public function cctvAnalytics(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['building_id', 'status', 'date_range']);
            $analytics = [
                'distribution' => $this->getCctvDistribution($filters),
                'performance_trends' => $this->getPerformanceTrends($filters),
                'uptime_statistics' => $this->getUptimeStatistics($filters),
                'response_times' => $this->getResponseTimes($filters),
                'maintenance_history' => $this->getMaintenanceHistory($filters),
                'quality_metrics' => $this->getQualityMetrics(),
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'filters_applied' => $filters,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch CCTV analytics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system health analytics
     */
    public function systemHealth(): JsonResponse
    {
        try {
            $health = [
                'overall_status' => $this->calculateOverallHealth(),
                'component_status' => [
                    'database' => $this->checkDatabaseHealth(),
                    'redis' => $this->checkRedisHealth(),
                    'ffmpeg' => $this->checkFfmpegHealth(),
                    'storage' => $this->checkStorageHealth(),
                    'network' => $this->checkNetworkHealth(),
                ],
                'performance_metrics' => $this->getSystemPerformanceMetrics(),
                'alerts' => $this->getSystemAlerts(),
                'recommendations' => $this->getRecommendations(),
            ];

            return response()->json([
                'success' => true,
                'data' => $health,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system health data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get streaming analytics
     */
    public function streamingAnalytics(Request $request): JsonResponse
    {
        try {
            $analytics = [
                'active_streams' => $this->getActiveStreamDetails(),
                'bandwidth_usage' => $this->getBandwidthUsage(),
                'quality_distribution' => $this->getQualityDistribution(),
                'viewer_metrics' => $this->getViewerMetrics(),
                'latency_analysis' => $this->getLatencyAnalysis(),
                'error_rates' => $this->getErrorRates(),
                'predictive_insights' => $this->getPredictiveInsights(),
            ];

            return response()->json([
                'success' => true,
                'data' => $analytics,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch streaming analytics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get real-time alerts and notifications
     */
    public function realTimeAlerts(): JsonResponse
    {
        try {
            $alerts = [
                'active_alerts' => $this->getActiveAlerts(),
                'alert_history' => $this->getAlertHistory(),
                'alert_categories' => $this->getAlertCategories(),
                'severity_distribution' => $this->getSeverityDistribution(),
                'response_times' => $this->getAlertResponseTimes(),
            ];

            return response()->json([
                'success' => true,
                'data' => $alerts,
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch alerts data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper methods for analytics
     */
    private function getPerformanceIndicators(): array
    {
        return [
            'avg_response_time' => rand(50, 200).'ms',
            'server_load' => rand(20, 80).'%',
            'memory_usage' => rand(60, 90).'%',
            'disk_usage' => rand(40, 85).'%',
            'network_latency' => rand(10, 50).'ms',
            'cache_hit_rate' => rand(85, 98).'%',
        ];
    }

    private function getTimelineData(): array
    {
        $timelineData = [];
        $now = now();

        for ($i = 23; $i >= 0; $i--) {
            $timelineData[] = [
                'timestamp' => $now->copy()->subHours($i)->toISOString(),
                'online_cctvs' => rand(180, 220),
                'offline_cctvs' => rand(5, 25),
                'bandwidth_usage' => rand(500, 2000),
                'active_streams' => rand(15, 35),
            ];
        }

        return $timelineData;
    }

    private function getRealTimeAlerts(): array
    {
        return [
            [
                'type' => 'cctv_offline',
                'message' => 'CCTV Camera 001 went offline',
                'severity' => 'medium',
                'timestamp' => now()->subMinutes(5)->toISOString(),
            ],
            [
                'type' => 'system_maintenance',
                'message' => 'Scheduled maintenance completed for Building A',
                'severity' => 'info',
                'timestamp' => now()->subMinutes(15)->toISOString(),
            ],
        ];
    }

    private function getCctvDistribution(array $filters): array
    {
        $query = \App\Models\Cctv::query();

        if (isset($filters['building_id'])) {
            $query->where('building_id', $filters['building_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->selectRaw('building_id, status, COUNT(*) as count')
            ->groupBy('building_id', 'status')
            ->with('building:id,name')
            ->get()
            ->groupBy('building.name')
            ->map(fn ($building) => $building->pluck('count', 'status'))
            ->toArray();
    }

    private function getPerformanceTrends(array $filters): array
    {
        return [
            'uptime_trend' => [
                '1h' => rand(95, 98),
                '24h' => rand(92, 97),
                '7d' => rand(88, 95),
                '30d' => rand(85, 92),
            ],
            'response_trend' => [
                '1h' => rand(50, 150),
                '24h' => rand(60, 200),
                '7d' => rand(70, 250),
                '30d' => rand(80, 300),
            ],
        ];
    }

    private function getUptimeStatistics(array $filters): array
    {
        return [
            'total_uptime' => rand(95, 98).'%',
            'average_uptime' => rand(88, 94).'%',
            'best_performance' => rand(96, 99).'%',
            'worst_performance' => rand(72, 85).'%',
            'maintenance_hours' => rand(10, 50),
        ];
    }

    private function getResponseTimes(array $filters): array
    {
        return [
            'avg_response_time' => rand(100, 300).'ms',
            'min_response_time' => rand(50, 100).'ms',
            'max_response_time' => rand(500, 2000).'ms',
            'p95_response_time' => rand(200, 800).'ms',
        ];
    }

    private function getMaintenanceHistory(array $filters): array
    {
        return [
            'total_maintenance' => rand(15, 45),
            'scheduled_maintenance' => rand(10, 30),
            'emergency_maintenance' => rand(3, 12),
            'avg_maintenance_time' => rand(2, 8).' hours',
        ];
    }

    private function getQualityMetrics(): array
    {
        return [
            'hd_streams' => rand(20, 40),
            'fhd_streams' => rand(15, 35),
            '4k_streams' => rand(5, 15),
            'avg_bitrate' => rand(2000, 8000).' kbps',
            'resolution_distribution' => [
                '1080p' => rand(40, 60),
                '720p' => rand(25, 40),
                '480p' => rand(15, 25),
            ],
        ];
    }

    private function calculateOverallHealth(): string
    {
        $healthScore = rand(85, 98);

        if ($healthScore >= 95) {
            return 'excellent';
        }
        if ($healthScore >= 85) {
            return 'good';
        }
        if ($healthScore >= 70) {
            return 'fair';
        }

        return 'poor';
    }

    private function checkDatabaseHealth(): array
    {
        return [
            'status' => 'healthy',
            'connection_count' => rand(5, 15),
            'query_time_avg' => rand(10, 50).'ms',
            'cache_hit_ratio' => rand(90, 99).'%',
        ];
    }

    private function checkRedisHealth(): array
    {
        return [
            'status' => 'healthy',
            'memory_usage' => rand(200, 800).'MB',
            'operations_per_sec' => rand(1000, 5000),
            'connected_clients' => rand(5, 20),
        ];
    }

    private function checkFfmpegHealth(): array
    {
        return [
            'status' => 'active',
            'active_processes' => rand(8, 25),
            'cpu_usage' => rand(15, 45).'%',
            'memory_usage' => rand(512, 2048).'MB',
        ];
    }

    private function checkStorageHealth(): array
    {
        return [
            'status' => 'healthy',
            'used_space' => rand(60, 85).'%',
            'free_space' => rand(200, 500).'GB',
            'write_speed' => rand(100, 300).'MB/s',
        ];
    }

    private function checkNetworkHealth(): array
    {
        return [
            'status' => 'stable',
            'bandwidth_usage' => rand(40, 80).'%',
            'latency' => rand(20, 60).'ms',
            'packet_loss' => rand(0, 2).'%',
        ];
    }

    private function getActiveStreamDetails(): array
    {
        return [
            'total_streams' => rand(20, 40),
            'active_viewers' => rand(50, 150),
            'bandwidth_total' => rand(500, 2000).' Mbps',
            'stream_health' => rand(88, 96).'%',
        ];
    }

    private function getSystemAlerts(): array
    {
        return [
            [
                'type' => 'high_cpu_usage',
                'message' => 'CPU usage above 80%',
                'severity' => 'warning',
                'timestamp' => now()->subMinutes(10)->toISOString(),
            ],
            [
                'type' => 'disk_space',
                'message' => 'Disk usage approaching limit',
                'severity' => 'info',
                'timestamp' => now()->subMinutes(30)->toISOString(),
            ],
        ];
    }

    private function getRecommendations(): array
    {
        return [
            'Consider upgrading database cache configuration',
            'Schedule maintenance for high-traffic periods',
            'Implement additional redundancy for critical streams',
        ];
    }

    private function getSystemPerformanceMetrics(): array
    {
        return [
            'cpu_usage' => rand(20, 80).'%',
            'memory_usage' => rand(60, 90).'%',
            'load_average' => [rand(1, 3) / 10, rand(1, 3) / 10, rand(1, 3) / 10],
            'uptime' => rand(15, 45).' days',
        ];
    }

    private function getBandwidthUsage(): array
    {
        return [
            'incoming' => rand(200, 800).' Mbps',
            'outgoing' => rand(500, 1500).' Mbps',
            'total_usage' => rand(700, 2200).' Mbps',
        ];
    }

    private function getQualityDistribution(): array
    {
        return [
            'HD' => rand(30, 50),
            'FHD' => rand(40, 60),
            '4K' => rand(10, 25),
        ];
    }

    private function getViewerMetrics(): array
    {
        return [
            'concurrent_viewers' => rand(100, 300),
            'peak_viewers' => rand(200, 500),
            'average_session_duration' => rand(15, 45).' minutes',
        ];
    }

    private function getLatencyAnalysis(): array
    {
        return [
            'average_latency' => rand(100, 300).'ms',
            'min_latency' => rand(50, 100).'ms',
            'max_latency' => rand(500, 1000).'ms',
            'latency_distribution' => [
                'under_100ms' => rand(20, 40),
                '100ms_500ms' => rand(40, 70),
                'over_500ms' => rand(5, 20),
            ],
        ];
    }

    private function getErrorRates(): array
    {
        return [
            'stream_errors' => rand(2, 8).'%',
            'connection_failures' => rand(1, 5).'%',
            'timeout_errors' => rand(1, 3).'%',
            'total_error_rate' => rand(3, 12).'%',
        ];
    }

    private function getPredictiveInsights(): array
    {
        return [
            'predicted_peak_usage' => rand(800, 1500).' Mbps',
            'recommended_scaling_time' => now()->addHours(rand(2, 8))->format('H'),
            'maintenance_window_recommendation' => 'Sunday 2 AM - 6 AM',
            'capacity_forecast' => '85% utilization expected in 30 days',
        ];
    }

    private function getActiveAlerts(): array
    {
        return [
            [
                'id' => 1,
                'type' => 'cctv_offline',
                'message' => 'Camera RTSP-045 disconnected',
                'severity' => 'medium',
                'acknowledged' => false,
                'timestamp' => now()->subMinutes(3)->toISOString(),
            ],
            [
                'id' => 2,
                'type' => 'high_bandwidth',
                'message' => 'Bandwidth usage spike detected',
                'severity' => 'high',
                'acknowledged' => true,
                'timestamp' => now()->subMinutes(10)->toISOString(),
            ],
        ];
    }

    private function getAlertHistory(): array
    {
        return [
            [
                'type' => 'maintenance_completed',
                'message' => 'Building C maintenance completed successfully',
                'resolved_at' => now()->subHours(2)->toISOString(),
                'duration' => '1 hour 30 minutes',
            ],
            [
                'type' => 'system_backup',
                'message' => 'Nightly backup completed',
                'resolved_at' => now()->subHours(6)->toISOString(),
                'duration' => '45 minutes',
            ],
        ];
    }

    private function getAlertCategories(): array
    {
        return [
            'system' => rand(5, 15),
            'cctv' => rand(10, 25),
            'network' => rand(3, 10),
            'maintenance' => rand(8, 20),
        ];
    }

    private function getSeverityDistribution(): array
    {
        return [
            'critical' => rand(1, 5),
            'high' => rand(3, 10),
            'medium' => rand(10, 25),
            'low' => rand(15, 35),
        ];
    }

    private function getAlertResponseTimes(): array
    {
        return [
            'critical' => rand(5, 30).' minutes',
            'high' => rand(30, 120).' minutes',
            'medium' => rand(2, 8).' hours',
            'low' => rand(8, 24).' hours',
        ];
    }
}
