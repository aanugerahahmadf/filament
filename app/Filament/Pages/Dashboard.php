<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): ?string
    {
        return 'bxs-dashboard';
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\CctvStatusChart::class,
            \App\Filament\Widgets\CctvStatusTrendChart::class,
            \App\Filament\Widgets\UserActivityChart::class,
            \App\Filament\Widgets\StreamingPerformanceChart::class,
            \App\Filament\Widgets\CctvOperationalTable::class,
            \App\Filament\Widgets\OfflineAlerts::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }
}
