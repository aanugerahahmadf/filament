<?php

namespace App\Console\Commands;

use App\Services\HealthCheckService;
use App\Services\NotificationService;
use App\Services\SystemMonitoringService;
use Illuminate\Console\Command;

class SystemHealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:health-check {--notify : Send notifications for critical issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check system health and report any issues';

    /**
     * Execute the console command.
     */
    public function handle(HealthCheckService $healthCheckService, SystemMonitoringService $monitoringService, NotificationService $notificationService)
    {
        $this->info('Starting system health check...');

        // Get system health
        $health = $healthCheckService->getSystemHealth();
        $systemMetrics = $monitoringService->getSystemMetrics();
        $cctvHealth = $monitoringService->getCctvHealth();
        $alertSummary = $monitoringService->getAlertSummary();

        // Display health status
        $this->line('System Health Status: '.$health['application']['status']);
        $this->line('Database Status: '.$health['database']['status']);
        $this->line('Storage Status: '.$health['storage']['status']);
        $this->line('CCTV Status: '.$health['cctvs']['status']);
        $this->line('Services Status: '.$health['services']['status']);

        // Display system metrics
        $this->line("\nSystem Metrics:");
        $this->line('CPU Usage: '.$systemMetrics['cpu_usage'].'%');
        $this->line('Memory Usage: '.$systemMetrics['memory_usage']['percentage'].'%');
        $this->line('Disk Usage: '.$systemMetrics['disk_usage']['percentage'].'%');

        // Display CCTV health
        $this->line("\nCCTV Health:");
        $this->line('Total CCTVs: '.$cctvHealth['total']);
        $this->line('Online CCTVs: '.$cctvHealth['online']);
        $this->line('Offline CCTVs: '.$cctvHealth['offline']);
        $this->line('Maintenance CCTVs: '.$cctvHealth['maintenance']);
        $this->line('Online Percentage: '.$cctvHealth['online_percentage'].'%');

        // Display alert summary
        $this->line("\nAlert Summary:");
        $this->line('Total Alerts: '.$alertSummary['total']);
        $this->line('Active Alerts: '.$alertSummary['active']);
        $this->line('Critical Alerts: '.$alertSummary['critical']);
        $this->line('High Alerts: '.$alertSummary['high']);

        // Check for critical issues
        $criticalIssues = [];

        if ($cctvHealth['status'] === 'critical') {
            $criticalIssues[] = "CCTV system is in critical state with only {$cctvHealth['online_percentage']}% cameras online";
        }

        if ($alertSummary['critical'] > 0) {
            $criticalIssues[] = "There are {$alertSummary['critical']} critical alerts that need immediate attention";
        }

        if (! empty($criticalIssues) && $this->option('notify')) {
            $this->line("\nSending notifications for critical issues...");

            foreach ($criticalIssues as $issue) {
                // In a real implementation, you would send actual notifications
                $this->warn("Would send notification: {$issue}");
            }
        }

        // Overall status
        $overallStatus = 'healthy';
        if (! empty($criticalIssues)) {
            $overallStatus = 'critical';
            $this->error('System health check completed with critical issues!');
        } else {
            $this->info('System health check completed successfully. System is healthy.');
        }

        return $overallStatus === 'healthy' ? Command::SUCCESS : Command::FAILURE;
    }
}
