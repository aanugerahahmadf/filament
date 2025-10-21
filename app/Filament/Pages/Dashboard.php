<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdditionalLineChart;
use App\Filament\Widgets\CctvOperationalTable;
use App\Filament\Widgets\CctvStatusChart;
use App\Filament\Widgets\CctvStatusTrendChart;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\OfflineAlerts;
use App\Filament\Widgets\StreamingPerformanceChart;
use App\Filament\Widgets\UserActivityChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): ?string
    {
        return 'bxs-dashboard';
    }

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
            CctvStatusChart::class,
            CctvStatusTrendChart::class,
            UserActivityChart::class,
            StreamingPerformanceChart::class,
            AdditionalLineChart::class, // Added the new line chart
            CctvOperationalTable::class,
            OfflineAlerts::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }
}
