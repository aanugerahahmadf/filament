<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoggingService
{
    public function logCctvActivity(string $action, array $data = []): void
    {
        $user = Auth::user();

        Log::channel('daily')->info("CCTV {$action}", [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function logMaintenanceActivity(string $action, array $data = []): void
    {
        $user = Auth::user();

        Log::channel('daily')->info("Maintenance {$action}", [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function logAlertActivity(string $action, array $data = []): void
    {
        $user = Auth::user();

        Log::channel('daily')->info("Alert {$action}", [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function logUserActivity(string $action, array $data = []): void
    {
        $user = Auth::user();

        Log::channel('daily')->info("User {$action}", [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function logSystemActivity(string $action, array $data = []): void
    {
        Log::channel('daily')->info("System {$action}", [
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function logError(string $message, array $context = []): void
    {
        Log::channel('daily')->error($message, $context);
    }

    public function logWarning(string $message, array $context = []): void
    {
        Log::channel('daily')->warning($message, $context);
    }

    public function logInfo(string $message, array $context = []): void
    {
        Log::channel('daily')->info($message, $context);
    }
}
