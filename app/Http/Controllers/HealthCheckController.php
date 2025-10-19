<?php

namespace App\Http\Controllers;

use App\Services\HealthCheckService;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    protected HealthCheckService $healthCheckService;

    public function __construct(HealthCheckService $healthCheckService)
    {
        $this->healthCheckService = $healthCheckService;
    }

    /**
     * Get system health status
     */
    public function index(): JsonResponse
    {
        $health = $this->healthCheckService->getSystemHealth();

        $status = collect($health)->contains(fn ($service) => $service['status'] === 'unhealthy')
            ? 'unhealthy'
            : 'healthy';

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toISOString(),
            'services' => $health,
        ]);
    }

    /**
     * Get alerts summary
     */
    public function alerts(): JsonResponse
    {
        $alerts = $this->healthCheckService->getAlertsSummary();

        return response()->json([
            'status' => 'success',
            'data' => $alerts,
        ]);
    }
}
