<?php

namespace App\Filament\Widgets;

use App\Models\Cctv;
use Filament\Widgets\ChartWidget;

class CctvStatusTrendChart extends ChartWidget
{
    protected ?string $heading = 'CCTV Status Trends';

    protected ?string $pollingInterval = '10s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Generate data for the last 7 days
        $dates = [];
        $onlineData = [];
        $offlineData = [];
        $maintenanceData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('M d');

            // In a real application, you would query historical data
            // For now, we'll generate sample data
            $onlineCount = Cctv::where('status', 'online')->count();
            $offlineCount = Cctv::where('status', 'offline')->count();
            $maintenanceCount = Cctv::where('status', 'maintenance')->count();

            // Add some variation to make it look like a trend
            $variation = rand(-5, 5);
            $onlineData[] = max(0, $onlineCount + $variation);
            $offlineData[] = max(0, $offlineCount + intval($variation / 2));
            $maintenanceData[] = max(0, $maintenanceCount + intval($variation / 3));
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Online',
                    'data' => $onlineData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Offline',
                    'data' => $offlineData,
                    'borderColor' => '#EF4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Maintenance',
                    'data' => $maintenanceData,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
