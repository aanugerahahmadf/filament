<?php

namespace App\Providers;

use App\Services\ApiResponseService;
use App\Services\AuditService;
use App\Services\BackupService;
use App\Services\CacheService;
use App\Services\CctvService;
use App\Services\DashboardService;
use App\Services\DashboardWidgetService;
use App\Services\EventService;
use App\Services\ExportService;
use App\Services\FileStorageService;
use App\Services\HealthCheckService;
use App\Services\LoggingService;
use App\Services\NotificationService;
use App\Services\ReportService;
use App\Services\SearchService;
use App\Services\SettingsService;
use App\Services\SmsService;
use App\Services\SystemMonitoringService;
use App\Services\ValidationService;
use App\Services\WhatsAppService;
use Illuminate\Support\ServiceProvider;

class AppServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Core services
        $this->app->singleton(ApiResponseService::class);
        $this->app->singleton(AuditService::class);
        $this->app->singleton(BackupService::class);
        $this->app->singleton(CacheService::class);
        $this->app->singleton(EventService::class);
        $this->app->singleton(FileStorageService::class);
        $this->app->singleton(LoggingService::class);
        $this->app->singleton(SearchService::class);
        $this->app->singleton(SettingsService::class);
        $this->app->singleton(ValidationService::class);

        // Business services
        $this->app->singleton(CctvService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(DashboardWidgetService::class);
        $this->app->singleton(ExportService::class);
        $this->app->singleton(HealthCheckService::class);
        $this->app->singleton(NotificationService::class);
        $this->app->singleton(ReportService::class);
        $this->app->singleton(SystemMonitoringService::class);

        // Communication services
        $this->app->singleton(SmsService::class);
        $this->app->singleton(WhatsAppService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
